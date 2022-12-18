<?php

declare(strict_types=1);

namespace GTAWalletApi\Action;

use GTAWalletApi\Components\Application\AbstractAction;
use GTAWalletApi\Components\Payment\PaymentManager;
use GTAWalletApi\Database\Entity\CreditRequest;

class GetPaymentAction extends AbstractAction
{
    public function __construct(
        private readonly PaymentManager $paymentManager
    ) {
    }

    public function handle(): CreditRequest
    {
        return $this->paymentManager->getPayment(request: $this->request, pathArgs: $this->args);
    }
}
