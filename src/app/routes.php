<?php

namespace StreamData\app;

use Slim\App;
use StreamData\app\controllers\{AppController, FinanceController};

return function (App $app) {
    $app->get('/', [AppController::class, 'info']);

    $app->group('/finance',  function ($app) {
        $app->post('/invoices/dispatch', [FinanceController::class, 'invoicesDispatch']);
    });
};
