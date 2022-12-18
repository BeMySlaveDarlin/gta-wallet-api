<?php

declare(strict_types=1);

namespace GTAWalletApi\Action;

use GTAWalletApi\Components\Application\AbstractAction;
use GTAWalletApi\Components\Payment\PaymentManager;

class AddPaymentAction extends AbstractAction
{
    public function __construct(
        private readonly PaymentManager $paymentManager
    ) {
    }

    public function handle(): array
    {
        return $this->paymentManager->addPayment(request: $this->request, pathArgs: $this->args);
    }
}
