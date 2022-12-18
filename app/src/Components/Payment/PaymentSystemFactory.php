<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Payment;

use GTAWalletApi\Components\Application\Config;
use GTAWalletApi\Components\Payment\Adapter\PaymentSystemAdapterInterface;

class PaymentSystemFactory
{
    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function getPaymentSystemAdapter(?string $paymentSystem = null): PaymentSystemAdapterInterface
    {
        try {
            $psAlias = $paymentSystem ?? $this->config->get(Config::PAYMENT)['defaultVendor'];
            $options = $this->config->get(Config::PAYMENT)['options'][$psAlias];
            $adapterClass = $options['class'];

            return new $adapterClass($options);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Error creating payment system adapter: ' . $throwable->getMessage());
        }
    }
}
