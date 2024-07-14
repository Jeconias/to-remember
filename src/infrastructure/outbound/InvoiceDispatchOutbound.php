<?php

namespace StreamData\infrastructure\outbound;

use Monolog\Logger;
use StreamData\app\AppLogger;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchOutboundAdapter;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchPaymentContent;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchInboundAdapter;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchPaymentType;
use StreamData\infrastructure\implementations\InvoiceDispatchPaymentContentImpl;
use StreamData\infrastructure\persistence\InDisk;
use StreamData\infrastructure\services\NotificationService;
use StreamData\infrastructure\services\PaymentService;

class InvoiceDispatchOutbound extends InvoiceDispatchOutboundAdapter
{
    protected Logger $logger;
    private InDisk $inMemory;
    private PaymentService $paymentService;
    private NotificationService $notificationService;

    function __construct(PaymentService $paymentService, NotificationService $notificationService, InDisk $inMemory)
    {
        $this->logger = AppLogger::get(InvoiceDispatchOutbound::class);
        $this->inMemory = $inMemory;
        $this->paymentService = $paymentService;
        $this->notificationService = $notificationService;
    }

    function generatePaymentType(InvoiceDispatchInboundAdapter $input, InvoiceDispatchPaymentType $paymentType): InvoiceDispatchPaymentContent
    {
        $element = $this->inMemory->get($input->getDebtId());

        if ($element && $element['generateStatus'] === 'DONE') {
            $this->logger->info(sprintf('"%s" has already been processed ( process = %s )', $input->getDebtId(), 'GeneratePayment'));
            return InvoiceDispatchPaymentContentImpl::builder()->success(true);
        }

        $this->saveProcessStatus($input->getDebtId(), 'IN_PROGRESS', null);

        $this->logger->debug(sprintf('"%s" is being processed ( process = %s )', $input->getDebtId(), 'GeneratePayment'));

        $result = $this->paymentService->generateInvoice($input->getDebtId());
        $success = strlen($result ?? '') > 0;

        if ($success) {
            $this->saveProcessStatus($input->getDebtId(), 'DONE', null);
        }

        $this->logger->debug(sprintf('"%s" was finished ( process = %s, success = %s )', $input->getDebtId(), 'GeneratePayment', $success));

        return InvoiceDispatchPaymentContentImpl::builder()->success($success)->contentAsBase64($result);
    }

    function sendNotification(InvoiceDispatchInboundAdapter $input, InvoiceDispatchPaymentContent $paymentContent): bool
    {
        $element = $this->inMemory->get($input->getDebtId());

        if ($element && $element['sentStatus'] === 'DONE') {
            $this->logger->debug(sprintf('"%s" has already been processed ( process = %s )', $input->getDebtId(), 'SendNotification'));
            return true;
        }

        $this->saveProcessStatus($input->getDebtId(),  null, 'IN_PROGRESS');

        $this->logger->debug(sprintf('"%s" is being processed ( process = %s )', $input->getDebtId(), 'SendNotification'));

        $success = $this->notificationService->sendNotification($input->getEmail(), $paymentContent);

        if ($success) {
            $this->saveProcessStatus($input->getDebtId(),  null, 'DONE');
        }

        $this->logger->debug(sprintf('"%s" was finished ( process = %s, success = %s )', $input->getDebtId(), 'SendNotification', $success));

        return $success;
    }

    private function saveProcessStatus(string $debtId, ?string $generateStatus, ?string $sentStatus)
    {
        $element = $this->inMemory->get($debtId);

        if (!$element) {
            $newElement = $this->getCacheTemplate($generateStatus, $sentStatus);
            $this->inMemory->set($debtId, $newElement);

            return $newElement;
        };

        $this->inMemory->set($debtId, $this->getCacheTemplate(
            $generateStatus ?? $element['generateStatus'],
            $sentStatus ?? $element['sentStatus']
        ));
    }

    private function getCacheTemplate(?string $generateStatus, ?string $sentStatus)
    {
        return [
            'generateStatus' => $generateStatus,
            'sentStatus' => $sentStatus,
        ];
    }
}
