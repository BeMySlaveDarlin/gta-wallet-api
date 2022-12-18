<?php

declare(strict_types=1);

namespace GTAWalletApi\Helper;

use Laminas\Diactoros\ServerRequest;

class RequestParser
{
    private array $queryParams;
    private array $postParams;
    private array $jsonParams;
    private array $allParams;

    public function __construct(
        private readonly ServerRequest $request
    ) {
        $this->queryParams = (array) ($this->request->getQueryParams() ?? []);
        $this->postParams = (array) ($this->request->getParsedBody() ?? []);
        $this->jsonParams = \json_decode($this->request->getBody()->getContents(), true) ?? [];

        $this->allParams = [];
        if (!empty($this->queryParams)) {
            $this->allParams = \array_merge($this->allParams, $this->queryParams);
        }
        if (!empty($this->postParams)) {
            $this->allParams = \array_merge($this->allParams, $this->postParams);
        }
        if (!empty($this->jsonParams)) {
            $this->allParams = \array_merge($this->allParams, $this->jsonParams);
        }
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        if (empty($this->allParams[$key]) && null === $default) {
            throw new \RuntimeException('No params found by key: ' . $key);
        }

        return $this->allParams[$key] ?? $default;
    }

    public function getJsonParam(string $key, mixed $default = null): mixed
    {
        if (empty($this->jsonParams[$key]) && null === $default) {
            throw new \RuntimeException('No params found by key: ' . $key);
        }

        return $this->jsonParams[$key] ?? $default;
    }

    public function getPostParam(string $key, mixed $default = null): mixed
    {
        if (empty($this->postParams[$key]) && null === $default) {
            throw new \RuntimeException('No params found by key: ' . $key);
        }

        return $this->postParams[$key] ?? $default;
    }

    public function getQueryParam(string $key, mixed $default = null): mixed
    {
        if (empty($this->queryParams[$key]) && null === $default) {
            throw new \RuntimeException('No params found by key: ' . $key);
        }

        return $this->queryParams[$key] ?? $default;
    }

    public function getJsonParams(): array
    {
        return $this->jsonParams;
    }

    public function getPostParams(): array
    {
        return $this->postParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParams(): array
    {
        return $this->allParams;
    }
}
