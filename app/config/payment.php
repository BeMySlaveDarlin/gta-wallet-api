<?php

declare(strict_types=1);

use GTAWalletApi\Components\Payment\Adapter\EnotPayAdapter;
use GTAWalletApi\Components\Payment\PaymentSystem;

return [
    'defaultVendor' => \getenv('BILLING_VENDOR'),
    'options' => [
        PaymentSystem::PS_ENOT_PAY => [
            'class' => EnotPayAdapter::class,
            EnotPayAdapter::PARAM_URL => 'https://enot.io/pay',
            EnotPayAdapter::PARAM_MERCHANT_ID => \getenv('ENOT_PAY_MERCHANT_ID'),
            EnotPayAdapter::PARAM_SECRET_KEY_OUT => \getenv('ENOT_PAY_SECRET_KEY_OUT'),
            EnotPayAdapter::PARAM_SECRET_KEY_IN => \getenv('ENOT_PAY_SECRET_KEY_IN'),
            EnotPayAdapter::PARAM_DESCRIPTION => 'Secret RP credits'
        ],
    ],
];
