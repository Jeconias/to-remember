<?php

namespace StreamData;

use DI\Container;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchAbstract;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchUseCase;
use StreamData\domain\useCases\invoiceDispatch\InvoiceDispatchUseCaseValidation;
use StreamData\infrastructure\outbound\InvoiceDispatchOutbound;
use StreamData\infrastructure\persistence\InDisk;
use StreamData\infrastructure\services\NotificationService;
use StreamData\infrastructure\services\PaymentService;

$container = new Container([
    InvoiceDispatchAbstract::class => function (): InvoiceDispatchAbstract {
        return new InvoiceDispatchUseCaseValidation(
            new InvoiceDispatchUseCase(
                new InvoiceDispatchOutbound(
                    new PaymentService(),
                    new NotificationService(),
                    InDisk::getInstance()
                )
            )
        );
    }
]);

return $container;
