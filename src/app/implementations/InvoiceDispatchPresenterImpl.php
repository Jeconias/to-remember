<?php

namespace StreamData\app\implementations;

use Exception;
use Nyholm\Psr7\Response;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchInboundAdapter;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchPresenter;

class InvoiceDispatchPresenterImpl extends InvoiceDispatchPresenter
{
    private Response $response;

    private function __construct(Response $response)
    {
        $this->response = $response;
    }

    static function create(Response $response)
    {
        return new InvoiceDispatchPresenterImpl($response);
    }

    function getResponse()
    {
        return $this->response;
    }

    function successful(InvoiceDispatchInboundAdapter $input): void
    {
        // do nothing
    }

    function failed(InvoiceDispatchInboundAdapter $input, ?Exception $expcetion): void
    {
        $this->response->getBody()->write(json_encode(array('message' => 'Failed', 'details' => ['debtid' => $input->getDebtId()], 'errors' => [$expcetion->getMessage()])));
        $this->response = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(422);
    }

    function rejected(InvoiceDispatchInboundAdapter $input, array $validations): void
    {
        $this->response->getBody()->write(json_encode(array('message' => 'Rejected', 'details' => ['debtid' => $input->getDebtId()], 'errors' => $validations)));
        $this->response = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
}
