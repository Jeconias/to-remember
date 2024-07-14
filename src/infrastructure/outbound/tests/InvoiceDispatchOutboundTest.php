<?php

namespace StreamData\infrastructure\outbound\tests;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use StreamData\app\dtos\InvoiceDispatchInboundImpl;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchPaymentType;
use StreamData\infrastructure\outbound\InvoiceDispatchOutbound;
use StreamData\infrastructure\persistence\InDisk;
use StreamData\infrastructure\services\NotificationService;
use StreamData\infrastructure\services\PaymentService;

final class InvoiceDispatchOutboundTest extends TestCase
{

    public function testShouldReturnTrueIfGeneratePaymentWasProcessed(): void
    {
        // Arrange
        $reflection = new ReflectionProperty(InvoiceDispatchOutbound::class, 'logger');
        $reflection->setAccessible(true);

        $loggerMock = $this->createMock(Logger::class);
        $inMemoryMock = $this->createMock(InDisk::class);
        $paymentServiceMock = $this->createMock(PaymentService::class);
        $notificationServiceMock = $this->createMock(NotificationService::class);

        $instance = new InvoiceDispatchOutbound($paymentServiceMock, $notificationServiceMock, $inMemoryMock);
        $reflection->setValue($instance, $loggerMock);

        $debtId = 'id-mocked';

        $inMemoryMock->expects($this->once())
            ->method('get')
            ->with($debtId)
            ->willReturn(['generateStatus' => 'DONE']);

        $loggerMock->expects($this->once())->method('info')->with('"id-mocked" has already been processed ( process = GeneratePayment )');

        // Act
        $result = $instance->generatePaymentType(
            InvoiceDispatchInboundImpl::builder()
                ->name('')
                ->governmentId('')
                ->email('')
                ->debtAmount('')
                ->debtDueDate('')
                ->debtId($debtId),
            InvoiceDispatchPaymentType::BOLETO
        );

        // Asserts
        $this->assertTrue($result->getSuccess(), 'teste');
    }
}
