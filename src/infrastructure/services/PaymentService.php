<?php

namespace StreamData\infrastructure\services;

class PaymentService
{
    function generateInvoice(string $debtId): ?string
    {
        return rand(1, 10) > 2 ? 'Genereted' : null;
    }
}
