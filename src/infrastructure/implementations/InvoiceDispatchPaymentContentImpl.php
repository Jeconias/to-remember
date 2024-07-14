<?php

namespace StreamData\infrastructure\implementations;

use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchPaymentContent;

class InvoiceDispatchPaymentContentImpl implements InvoiceDispatchPaymentContent
{
    private bool $_success;

    private ?string $_contentAsBase64;

    private ?string $_contentUrl;

    private function __construct()
    {
        // empty
    }

    static function builder()
    {
        return new InvoiceDispatchPaymentContentImpl();
    }

    function success(bool $success)
    {
        $this->_success = $success;
        return $this;
    }

    function contentUrl(?string $contentUrl)
    {
        $this->_contentUrl = $contentUrl;
        return $this;
    }

    function contentAsBase64(?string $contentAsBase64)
    {
        $this->_contentAsBase64 = $contentAsBase64;
        return $this;
    }

    function getSuccess(): bool
    {
        return $this->_success;
    }

    function getContentAsBase64(): string
    {
        return $this->_contentAsBase64;
    }

    function getContentUrl(): string
    {
        return $this->_contentUrl;
    }
}
