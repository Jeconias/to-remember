<?php

namespace StreamData\domain\useCases\invoiceDispatch;

abstract class InvoiceDispatchAbstract
{
  protected InvoiceDispatchPresenter $presenter;

  abstract function execute(InvoiceDispatchInboundAdapter $input): void;

  public function setPresenter(InvoiceDispatchPresenter $presenter): InvoiceDispatchAbstract
  {
    $this->presenter = $presenter;
    return $this;
  }
}
