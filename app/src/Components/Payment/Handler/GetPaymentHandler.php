<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment\Handler;

use GTAWalletApi\Components\Application\Database;
use GTAWalletApi\Database\Entity\CreditRequest;
use Laminas\Diactoros\ServerRequest;

class GetPaymentHandler implements PaymentHandlerInterface
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function handle(ServerRequest $request, array $pathArgs = []): CreditRequest
    {
        return $this->getCreditRequest($pathArgs['id'] ?? null);
    }

    private function getCreditRequest(mixed $id = null): CreditRequest
    {
        $repository = $this->database->getORM()->getRepository(entity: CreditRequest::class);
        $creditRequest = $repository->findByPK($id);
        if (null === $creditRequest) {
            throw new \RuntimeException('Not found credit request #' . $id);
        }

        return $creditRequest;
    }
}
