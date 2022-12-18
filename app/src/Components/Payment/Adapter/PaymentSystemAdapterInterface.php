<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment\Adapter;

use GTAWalletApi\Database\Entity\CreditRequest;
use GTAWalletApi\Helper\RequestParser;

interface PaymentSystemAdapterInterface
{
    public function getAlias(): string;

    public function getDefaultDescription(): ?string;

    public function processCallback(
        RequestParser $request,
        CreditRequest $creditRequest,
        array $options = []
    ): mixed;

    public function sendRequest(CreditRequest $creditRequest): mixed;
}
