<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment\Handler;

use GTAWalletApi\Components\Application\Database;
use GTAWalletApi\Components\Payment\PaymentSystemFactory;
use GTAWalletApi\Database\Entity\CreditRequest;
use GTAWalletApi\Database\Entity\Transaction;
use GTAWalletApi\Database\Entity\Wallet;
use GTAWalletApi\Helper\RequestParser;
use Laminas\Diactoros\ServerRequest;

class UpdatePaymentHandler implements PaymentHandlerInterface
{
    public function __construct(
        private readonly Database $database,
        private readonly PaymentSystemFactory $factory,
    ) {
    }

    public function handle(ServerRequest $request, array $pathArgs = [])
    {
        $rp = new RequestParser($request);
        $adapter = $this->factory->getPaymentSystemAdapter(paymentSystem: $pathArgs['paymentSystem'] ?? null);

        $creditRequest = $this->getCreditRequest($rp);
        $wallet = $this->getWallet($creditRequest);
        $transaction = $this->getTransaction($creditRequest, $wallet);

        $callbackResult = $adapter->processCallback(request: $rp, creditRequest: $creditRequest, options: $pathArgs);

        $this->database
            ->getEntityManager()
            ->persist(entity: $creditRequest)
            ->persist(entity: $wallet)
            ->persist(entity: $transaction)
            ->run();

        return $callbackResult;
    }

    private function getTransaction(CreditRequest $creditRequest, Wallet $wallet): Transaction
    {
        $transaction = new Transaction();
        $transaction->accountId = $wallet->accountId;
        $transaction->amount = $creditRequest->credits->getAmountReal();
        $transaction->description = $creditRequest->description;
        $transaction->operation = 10000;
        $transaction->entityId = 0;

        return $transaction;
    }

    private function getCreditRequest(RequestParser $rp): CreditRequest
    {
        $repository = $this->database->getORM()->getRepository(entity: CreditRequest::class);
        $creditRequest = $repository->findByPK($rp->getParam(key: 'merchant_id'));
        if (null === $creditRequest) {
            throw new \RuntimeException('Not found credit request #' . $rp->getParam(key: 'merchant_id'));
        }
        if ($creditRequest->status === CreditRequest::STATUS_SUCCESS) {
            throw new \RuntimeException('Credit request already processed #' . $rp->getParam(key: 'merchant_id'));
        }

        $creditRequest->callback = \json_encode($rp->getParams());
        $creditRequest->status = CreditRequest::STATUS_SUCCESS;

        return $creditRequest;
    }

    private function getWallet(CreditRequest $creditRequest): Wallet
    {
        $repository = $this->database->getORM()->getRepository(entity: Wallet::class);
        $wallet = $repository->findOne(['accountId' => $creditRequest->accountId]);
        if (null === $wallet) {
            throw new \RuntimeException('Not found wallet for #' . $creditRequest->accountId);
        }

        $wallet->credits += $creditRequest->credits->getAmountReal();

        return $wallet;
    }
}
