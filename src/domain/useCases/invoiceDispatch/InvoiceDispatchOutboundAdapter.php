<?php

namespace StreamData\domain\useCases\invoiceDispatch;

abstract class InvoiceDispatchOutboundAdapter
{
    abstract function generatePaymentType(InvoiceDispatchInboundAdapter $input, InvoiceDispatchPaymentType $paymentType): InvoiceDispatchPaymentContent;
    abstract function sendNotification(InvoiceDispatchInboundAdapter $input, InvoiceDispatchPaymentContent $paymentContent): bool;
}
