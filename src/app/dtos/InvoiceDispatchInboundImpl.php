<?php

namespace StreamData\app\dtos;

use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchInboundAdapter;

class InvoiceDispatchInboundImpl implements InvoiceDispatchInboundAdapter
{

    private string $_name;

    private string $_governmentId;

    private string $_email;

    private string $_debtAmount;

    private string $_debtDueDate;

    private string $_debtID;

    private function __construct()
    {
        // empty
    }

    static function builder()
    {
        return new InvoiceDispatchInboundImpl();
    }

    function name(string $name)
    {
        $this->_name = $name;
        return $this;
    }

    function governmentId(string $governmentId)
    {
        $this->_governmentId = $governmentId;
        return $this;
    }

    function email(string $email)
    {
        $this->_email = $email;
        return $this;
    }

    function debtAmount(string $debtAmount)
    {
        $this->_debtAmount = $debtAmount;
        return $this;
    }

    function debtDueDate(string $debtDueDate)
    {
        $this->_debtDueDate = $debtDueDate;
        return $this;
    }

    function debtId(string $debtID)
    {
        $this->_debtID = $debtID;
        return $this;
    }

    function getName(): string
    {
        return $this->_name;
    }

    function getGovernmentId(): string
    {
        return $this->_governmentId;
    }

    function getEmail(): string
    {
        return $this->_email;
    }

    function getDebtAmount(): string
    {
        return $this->_debtAmount;
    }

    function getDebtDueDate(): string
    {
        return $this->_debtDueDate;
    }

    function getDebtId(): string
    {
        return $this->_debtID;
    }
}
