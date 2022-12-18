<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment;

use GTAWalletApi\Components\Application\Database;
use GTAWalletApi\Components\Payment\Handler\AddPaymentHandler;
use GTAWalletApi\Components\Payment\Handler\GetPaymentHandler;
use GTAWalletApi\Components\Payment\Handler\UpdatePaymentHandler;
use Laminas\Diactoros\ServerRequest;

class PaymentManager
{
    public function __construct(
        private readonly Database $database,
        private readonly PaymentSystemFactory $factory,
    ) {
    }

    public function getPayment(ServerRequest $request, array $pathArgs = []): mixed
    {
        try {
            return (new GetPaymentHandler(database: $this->database))->handle(request: $request, pathArgs: $pathArgs);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Error getting credit request. ' . $throwable->getMessage());
        }
    }

    public function addPayment(ServerRequest $request, array $pathArgs = []): array
    {
        $this->database->getDbal()->database()->begin();
        try {
            $handler = new AddPaymentHandler(database: $this->database, factory: $this->factory);
            $result = $handler->handle(request: $request, pathArgs: $pathArgs);

            $this->database->getDbal()->database()->commit();

            return $result;
        } catch (\Throwable $throwable) {
            $this->database->getDbal()->database()->rollback();

            throw new \RuntimeException('Error creating credit request. ' . $throwable->getMessage());
        }
    }

    public function updatePayment(ServerRequest $request, array $pathArgs = []): mixed
    {
        $this->database->getDbal()->database()->begin();
        try {
            $handler = new UpdatePaymentHandler(database: $this->database, factory: $this->factory);
            $result = $handler->handle(request: $request, pathArgs: $pathArgs);

            $this->database->getDbal()->database()->commit();

            return $result;
        } catch (\Throwable $throwable) {
            $this->database->getDbal()->database()->rollback();

            throw new \RuntimeException('Error updating credit request. ' . $throwable->getMessage());
        }
    }
}
