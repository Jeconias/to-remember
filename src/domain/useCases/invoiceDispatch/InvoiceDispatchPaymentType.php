<?php

namespace StreamData\domain\useCases\invoiceDispatch;


enum InvoiceDispatchPaymentType: string
{
    case BOLETO = 'BOLETO';
    case PIX = 'PIX';
}
