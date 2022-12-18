<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Application;

class Config
{
    public const ROUTES = 'routes';
    public const DB_PARAMS = 'db_params';
    public const DB_SCHEMA_OPTIONS = 'schemaOptions';
    public const PAYMENT = 'payment';

    public array $configs = [];

    public function __construct(array $data = [])
    {
        $params = $data ?: require CNF_PATH . 'config.php';
        foreach ($params as $key => $values) {
            $this->configs[$key] = $values;
        }
    }

    public function get(string $key): mixed
    {
        return $this->configs[$key] ?? null;
    }
}
