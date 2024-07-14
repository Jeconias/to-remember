<?php

namespace StreamData\domain\useCases\invoiceDispatch;

interface InvoiceDispatchPaymentContent
{
    function getSuccess(): bool;

    function getContentAsBase64(): string;

    function getContentUrl(): string;
}
