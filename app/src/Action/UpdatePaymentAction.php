<?php

declare(strict_types=1);

namespace GTAWalletApi\Action;

use GTAWalletApi\Components\Application\AbstractAction;
use GTAWalletApi\Components\Payment\PaymentManager;

class UpdatePaymentAction extends AbstractAction
{
    public function __construct(
        private readonly PaymentManager $paymentManager
    ) {
    }

    public function handle(): mixed
    {
        return $this->paymentManager->updatePayment(request: $this->request, pathArgs: $this->args);
    }
}
