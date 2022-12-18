<?php

declare(strict_types=1);

namespace GTAWalletApi\Helper;

class JsonResponse
{
    private const JSON_OPTS = JSON_THROW_ON_ERROR | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE;

    public static function encode(mixed $data = null, int $code = 200, ?string $message = null): string
    {
        if (\is_scalar($data)) {
            $data = ['data' => $data];
        }

        return \json_encode(['status_code' => $code, 'message' => $message, 'payload' => $data], self::JSON_OPTS);
    }
}
