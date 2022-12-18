<?php

declare(strict_types=1);

use GTAWalletApi\Action\AddPaymentAction;
use GTAWalletApi\Action\GetPaymentAction;
use GTAWalletApi\Action\IndexAction;
use GTAWalletApi\Components\Application\Application;
use GTAWalletApi\Components\Application\Config;
use GTAWalletApi\Components\Application\Database;
use GTAWalletApi\Components\Application\Dispatcher;
use GTAWalletApi\Components\Payment\PaymentManager;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Message\ResponseFactoryInterface;

use function DI\autowire;

return [
    //Actions
    AddPaymentAction::class => autowire(AddPaymentAction::class),
    GetPaymentAction::class => autowire(GetPaymentAction::class),
    IndexAction::class => autowire(IndexAction::class),

    //Components
    Application::class => autowire(Application::class),
    Config::class => autowire(Config::class),
    Database::class => autowire(Database::class),
    Dispatcher::class => autowire(Dispatcher::class),
    PaymentManager::class => autowire(PaymentManager::class),
    Router::class => autowire(Router::class),

    //Vendor
    ResponseFactoryInterface::class => autowire(ResponseFactory::class),
    StrategyInterface::class => autowire(JsonStrategy::class),
];
