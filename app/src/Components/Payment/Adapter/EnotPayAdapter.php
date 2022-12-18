<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment\Adapter;

use GTAWalletApi\Components\Payment\PaymentSystem;
use GTAWalletApi\Database\Entity\CreditRequest;
use GTAWalletApi\Database\Type\MoneyTypecast;
use GTAWalletApi\Helper\MoneyValue;
use GTAWalletApi\Helper\RequestParser;

class EnotPayAdapter implements PaymentSystemAdapterInterface
{
    public const PARAM_URL = 'url';
    public const PARAM_MERCHANT_ID = 'merchantId';
    public const PARAM_SECRET_KEY_OUT = 'secretKeyOut';
    public const PARAM_SECRET_KEY_IN = 'secretKeyIn';
    public const PARAM_DESCRIPTION = 'description';

    public function __construct(
        private readonly array $params = []
    ) {
    }

    public function getAlias(): string
    {
        return PaymentSystem::PS_ENOT_PAY;
    }

    public function getDefaultDescription(): ?string
    {
        return $this->params[self::PARAM_DESCRIPTION] ?? null;
    }

    public function processCallback(RequestParser $request, CreditRequest $creditRequest, array $options = []): bool
    {
        $this->checkCallback($request, $creditRequest, $options);

        $amountFinal = new MoneyValue(
            amount: $request->getParam(key: 'credited'),
            currency: $request->getParam(key: 'currency', default: MoneyTypecast::DEFAULT_CURRENCY)
        );
        $commission = new MoneyValue(
            amount: $request->getParam(key: 'commission'),
            currency: $request->getParam(key: 'currency', default: MoneyTypecast::DEFAULT_CURRENCY)
        );
        $creditRequest->extId = (string) $request->getParam('intid');
        $creditRequest->amountFinal = $amountFinal;
        $creditRequest->commission = $commission;
        $creditRequest->commissionType = $request->getParam(key: 'commission_pay', default: 'shop');

        return true;
    }

    public function sendRequest(CreditRequest $creditRequest): array
    {
        $signature = \md5(
            \implode(':', [
                $this->params[self::PARAM_MERCHANT_ID],
                $creditRequest->amount->getAmountReal(),
                $this->params[self::PARAM_SECRET_KEY_OUT],
                $creditRequest->id,
            ])
        );

        $options = [
            'm' => $this->params[self::PARAM_MERCHANT_ID],
            'oa' => $creditRequest->amount->getAmountReal(),
            'o' => $creditRequest->id,
            's' => $signature,
            'cr' => $creditRequest->amount->getCurrency(),
            'c' => $creditRequest->description,
            'cf' => [
                'ps' => $creditRequest->paymentSystem,
                'uid' => $creditRequest->accountId,
                'uuid' => $creditRequest->uuid->toString(),
                'creds' => $creditRequest->credits->getAmountReal(),
            ],
        ];

        return [
            'url' => $this->params[self::PARAM_URL] . '?' . \http_build_query($options),
        ];
    }

    private function checkCallback(RequestParser $request, CreditRequest $creditRequest, array $options = []): void
    {
        $customFields = $request->getParam(key: 'custom_field', default: []);
        $ps = $customFields['ps'] ?? null;
        if ($creditRequest->paymentSystem !== $ps) {
            throw new \RuntimeException('Credit request payment system is invalid');
        }
        $optionsPs = $options['paymentSystem'] ?? null;
        if (null !== $optionsPs && $optionsPs !== $creditRequest->paymentSystem) {
            throw new \RuntimeException('Wrong webhook used');
        }

        $uid = $customFields['uid'] ?? null;
        if ($creditRequest->accountId !== $uid) {
            throw new \RuntimeException('Credit request payment system is invalid');
        }

        $uuid = $customFields['uuid'] ?? null;
        if ($creditRequest->uuid->toString() !== $uuid) {
            throw new \RuntimeException('Credit request uuid is invalid');
        }

        $signature = \md5(
            \implode(':', [
                $request->getParam(key: 'merchant'),
                $request->getParam(key: 'amount'),
                $this->params[self::PARAM_SECRET_KEY_IN],
                $request->getParam(key: 'merchant_id'),
            ])
        );
        $signIn = $request->getParam(key: 'sign_2');
        if ($signature !== $signIn) {
            //throw new \RuntimeException('Credit request signature is invalid');
        }
    }
}
