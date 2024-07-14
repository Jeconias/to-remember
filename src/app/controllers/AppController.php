<?php

namespace StreamData\app\controllers;

use Nyholm\Psr7\{ServerRequest as Request, Response};

class AppController
{
    public function info(Request $request, Response $response)
    {
        $response->getBody()->write(json_encode(array('message' => 'Ok')));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
