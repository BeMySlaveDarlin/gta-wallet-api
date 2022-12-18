<?php

declare(strict_types=1);

use GTAWalletApi\Components\Application\Config;

return [
    Config::PAYMENT => require 'payment.php',
    Config::ROUTES => require 'routes.php',
    Config::DB_PARAMS => require 'database/config.php',
    Config::DB_SCHEMA_OPTIONS => require 'database/schema.php',
];
