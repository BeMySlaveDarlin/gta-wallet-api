<?php

declare(strict_types=1);

use GTAWalletApi\Action\AddPaymentAction;
use GTAWalletApi\Action\GetPaymentAction;
use GTAWalletApi\Action\IndexAction;
use GTAWalletApi\Action\UpdatePaymentAction;
use GTAWalletApi\Components\Application\Dispatcher;

return [
    [
        'methods' => ['GET'],
        'type' => Dispatcher::TYPE_ROUTE,
        'path' => '/',
        'handler' => IndexAction::class,
    ],
    [
        'methods' => ['POST'],
        'type' => Dispatcher::TYPE_ROUTE,
        'path' => '/payment',
        'handler' => AddPaymentAction::class,
    ],
    [
        'methods' => ['GET'],
        'type' => Dispatcher::TYPE_ROUTE,
        'path' => '/payment/{id}',
        'handler' => GetPaymentAction::class,
    ],
    [
        'methods' => ['POST'],
        'type' => Dispatcher::TYPE_ROUTE,
        'path' => '/callback/{paymentSystem}',
        'handler' => UpdatePaymentAction::class,
    ],
];
