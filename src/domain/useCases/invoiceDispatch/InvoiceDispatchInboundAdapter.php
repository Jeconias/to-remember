<?php

namespace StreamData\domain\useCases\invoiceDispatch;

interface InvoiceDispatchInboundAdapter
{
    function getName(): string;

    function getGovernmentId(): string;

    function getEmail(): string;

    function getDebtAmount(): string;

    function getDebtDueDate(): string;

    function getDebtId(): string;
}
