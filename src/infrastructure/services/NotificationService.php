<?php

namespace StreamData\infrastructure\services;

use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchPaymentContent;

class NotificationService
{
    function sendNotification(string $email, InvoiceDispatchPaymentContent $invoice): bool
    {
        // Random status
        return rand(1, 10) > 2 ? true : false;
    }
}
