<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment\Handler;

use GTAWalletApi\Components\Application\Database;
use GTAWalletApi\Components\Payment\Adapter\PaymentSystemAdapterInterface;
use GTAWalletApi\Components\Payment\PaymentSystemFactory;
use GTAWalletApi\Database\Entity\CreditRequest;
use GTAWalletApi\Database\Type\MoneyTypecast;
use GTAWalletApi\Helper\MoneyValue;
use GTAWalletApi\Helper\RequestParser;
use Laminas\Diactoros\ServerRequest;
use Ramsey\Uuid\Uuid;

class AddPaymentHandler implements PaymentHandlerInterface
{
    public function __construct(
        private readonly Database $database,
        private readonly PaymentSystemFactory $factory,
    ) {
    }

    public function handle(ServerRequest $request, array $pathArgs = [])
    {
        $rp = new RequestParser(request: $request);
        $adapter = $this->factory->getPaymentSystemAdapter(paymentSystem: $pathArgs['paymentSystem'] ?? null);
        $creditRequest = $this->createCreditRequest(rp: $rp, adapter: $adapter);

        return $adapter->sendRequest(creditRequest: $creditRequest);
    }

    private function createCreditRequest(RequestParser $rp, PaymentSystemAdapterInterface $adapter): CreditRequest
    {
        $amount = new MoneyValue(
            amount: $rp->getParam(key: 'amount', default: 0),
            currency: $rp->getParam(key: 'currency', default: MoneyTypecast::DEFAULT_CURRENCY)
        );
        $credits = new MoneyValue(
            amount: $rp->getParam(key: 'credits', default: $rp->getParam(key: 'amount')),
            currency: MoneyTypecast::CURRENCY_CREDITS
        );

        $creditRequest = new CreditRequest();
        $creditRequest->status = CreditRequest::STATUS_NEW;
        $creditRequest->uuid = Uuid::getFactory()->uuid6();
        $creditRequest->accountId = $rp->getParam(key: 'accountId');
        $creditRequest->paymentSystem = $adapter->getAlias();
        $creditRequest->extId = null;
        $creditRequest->credits = $credits;
        $creditRequest->amount = $amount;
        $creditRequest->amountFinal = $amount;
        $creditRequest->request = \json_encode($rp->getParams());
        $creditRequest->description = $rp->getParam(
            key: 'description',
            default: $adapter->getDefaultDescription()
        );

        $this->database
            ->getEntityManager()
            ->persist(entity: $creditRequest)
            ->run();

        return $creditRequest;
    }
}
