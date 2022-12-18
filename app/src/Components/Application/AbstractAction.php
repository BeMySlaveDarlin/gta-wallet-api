<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Application;

use GTAWalletApi\Helper\JsonResponse;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractAction
{
    protected ServerRequest $request;
    protected array $args = [];
    protected Response $response;

    public function __invoke(ServerRequest $request, array $args = []): ResponseInterface
    {
        $this->request = $request;
        $this->args = $args;

        $response = new Response();
        $response->getBody()->write(
            JsonResponse::encode(
                data: $this->handle(),
                message: 'Ok'
            )
        );

        return $response;
    }

    abstract public function handle(): mixed;
}
