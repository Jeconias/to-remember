<?php

namespace StreamData\domain\useCases\invoiceDispatch;

class InvoiceDispatchUseCaseValidation extends InvoiceDispatchAbstract
{
    private InvoiceDispatchUseCase $useCase;

    function __construct(InvoiceDispatchUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * @Override
     */
    function setPresenter(InvoiceDispatchPresenter $presenter): InvoiceDispatchAbstract
    {
        $this->presenter = $presenter;
        $this->useCase->setPresenter($presenter);
        return $this;
    }

    function execute(InvoiceDispatchInboundAdapter $input): void
    {
        $errors = $this->isValid($input);

        if (count($errors) > 0) {
            $this->presenter->rejected($input, $errors);
            return;
        }

        $this->useCase->execute($input);
    }

    /**
     * @description Simple validation
     */
    private function isValid(InvoiceDispatchInboundAdapter $input): array
    {

        $values = array(
            'name' => $input->getName(),
            'governmentId' => $input->getGovernmentId(),
            'email' => $input->getEmail(),
            'debtAmount' => $input->getDebtAmount(),
            'debtDueDate' => $input->getDebtDueDate(),
            'debtID' => $input->getDebtID()
        );

        return array_map(fn ($value) => sprintf('"%s" is invalid', $value), array_keys(array_filter($values, fn ($value) => strlen($value) === 0)));
    }
}
