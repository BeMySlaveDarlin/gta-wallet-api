<?php

declare(strict_types=1);

use Cycle\Database\Config\MySQL\TcpConnectionConfig;
use Cycle\Database\Config\MySQLDriverConfig;

return [
    'default' => 'test_secretrp',
    'databases' => [
        'test_secretrp' => ['connection' => 'mysql_db'],
    ],
    'connections' => [
        'mysql_db' => new MySQLDriverConfig(
            connection: new TcpConnectionConfig(
                database: \getenv('MYSQL_DATABASE'),
                host: \getenv('MYSQL_HOST'),
                port: (int) \getenv('MYSQL_PORT'),
                user: \getenv('MYSQL_USER'),
                password: \getenv('MYSQL_PASSWORD'),
            ),
            queryCache: true
        ),
    ],
];
