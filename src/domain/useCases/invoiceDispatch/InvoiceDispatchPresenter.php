<?php

namespace StreamData\domain\useCases\invoiceDispatch;

use Exception;

abstract class InvoiceDispatchPresenter
{
    abstract function successful(InvoiceDispatchInboundAdapter $input): void;
    abstract function failed(InvoiceDispatchInboundAdapter $input, ?Exception $expcetion): void;
    abstract function rejected(InvoiceDispatchInboundAdapter $input, array $validations): void;
}
