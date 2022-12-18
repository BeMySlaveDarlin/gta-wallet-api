<?php

use DI\ContainerBuilder;
use GTAWalletApi\Components\Application\Application;

const DS = DIRECTORY_SEPARATOR;
define('BASE_PATH', dirname(__DIR__) . DS);
define('SERVER_NAME', $_SERVER['SERVER_NAME'] ?? php_uname('n'));
const SRC_PATH = BASE_PATH . 'src' . DS;
const CNF_PATH = BASE_PATH . 'config' . DS;
const VENDOR_PATH = BASE_PATH . 'vendor' . DS;

include VENDOR_PATH . 'autoload.php';

try {
    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->addDefinitions(require CNF_PATH . 'container.php');
    $container = $builder->build();

    $application = $container->get(Application::class);
    $application->handle();
} catch (\Throwable $throwable) {
    echo $throwable->getMessage() . PHP_EOL;
    echo $throwable->getTraceAsString();
}
