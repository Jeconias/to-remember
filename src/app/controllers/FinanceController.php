<?php

namespace StreamData\app\controllers;

use Nyholm\Psr7\{ServerRequest as Request, Response};
use Psr\Container\ContainerInterface;
use StreamData\app\dtos\InvoiceDispatchInboundImpl;
use StreamData\app\implementations\InvoiceDispatchPresenterImpl;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchAbstract;

final class FinanceController
{
    private ContainerInterface $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function invoicesDispatch(Request $request, Response $response)
    {
        $files = $request->getUploadedFiles();

        if (count($files) < 1) {
            $response->getBody()->write(json_encode(array('message' => 'Please enter the input file to process')));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (!$this->container->has(InvoiceDispatchAbstract::class)) {
            $response->getBody()->write(json_encode(array('message' => 'Dependencies not found')));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }

        $presenter = InvoiceDispatchPresenterImpl::create($response);
        $useCase = $this->getInvoiceDispatchUseCase()->setPresenter($presenter);

        while ($file = array_pop($files)) {
            $stream = $file->getStream();
            $detach = $stream->detach();

            $isHead = true;
            $columns = array();

            while ($row = fgetcsv($detach, 0, ',')) {
                if ($isHead) {
                    $head = array_map('strtolower', $row);

                    $columns['name'] = array_search('name', $head);
                    $columns['governmentid'] = array_search('governmentid', $head);
                    $columns['email'] = array_search('email', $head);
                    $columns['debtamount'] = array_search('debtamount', $head);
                    $columns['debtduedate'] = array_search('debtduedate', $head);
                    $columns['debtid'] = array_search('debtid', $head);

                    $isHead = false;
                    continue;
                }


                $useCase->execute(
                    InvoiceDispatchInboundImpl::builder()
                        ->name($row[$columns['name']])
                        ->governmentId($row[$columns['governmentid']])
                        ->email($row[$columns['email']])
                        ->debtAmount($row[$columns['debtamount']])
                        ->debtDueDate($row[$columns['debtduedate']])
                        ->debtID($row[$columns['debtid']])
                );
            }

            fclose($detach);
            $stream->close();
        }

        return $presenter->getResponse();
    }

    private function getInvoiceDispatchUseCase(): InvoiceDispatchAbstract
    {
        return $this->container->get(InvoiceDispatchAbstract::class);
    }
}
