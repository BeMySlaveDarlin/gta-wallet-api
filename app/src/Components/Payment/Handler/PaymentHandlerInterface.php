<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment\Handler;

use Laminas\Diactoros\ServerRequest;

interface PaymentHandlerInterface
{
    public function handle(ServerRequest $request, array $pathArgs = []);
}
