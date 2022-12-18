<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Application;

use GTAWalletApi\Helper\JsonResponse;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

class Application
{
    public function __construct(
        private readonly Dispatcher $dispatcher
    ) {
    }

    public function handle(): void
    {
        $emitter = new SapiEmitter();
        try {
            $request = ServerRequestFactory::fromGlobals(
                server: $_SERVER,
                query: $_GET,
                body: $_POST,
                cookies: $_COOKIE,
                files: $_FILES
            );
            $response = $this->dispatcher->dispatch(request: $request);
        } catch (\Throwable $throwable) {
            $response = new Response();
            $response->getBody()->write(
                JsonResponse::encode(
                    code: $throwable->getCode(),
                    message: $throwable->getMessage()
                )
            );
            $response->withStatus(code: 500);
        }

        $emitter->emit($response);
    }
}
