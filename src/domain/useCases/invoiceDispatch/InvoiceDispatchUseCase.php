<?php

namespace StreamData\domain\useCases\invoiceDispatch;

use Exception;

class InvoiceDispatchUseCase extends InvoiceDispatchAbstract
{
    private InvoiceDispatchOutboundAdapter $adapter;

    function __construct(InvoiceDispatchOutboundAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    function execute(InvoiceDispatchInboundAdapter $input): void
    {
        try {
            $result = $this->adapter->generatePaymentType($input, InvoiceDispatchPaymentType::BOLETO);

            if (!$result->getSuccess()) {
                $this->presenter->failed($input, new Exception('Failed on generate payment'));
                return;
            }

            $wasSent = $this->adapter->sendNotification($input, $result);

            if (!$wasSent) {
                $this->presenter->failed($input, new Exception('Failed on send notification'));
                return;
            }

            $this->presenter->successful($input);
        } catch (Exception $err) {
            $this->presenter->failed($input, $err);
        }
    }
}
