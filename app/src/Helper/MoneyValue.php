<?php

declare(strict_types=1);

namespace GTAWalletApi\Helper;

use Cycle\Database\DatabaseInterface;
use Cycle\Database\Injection\ValueInterface;
use GTAWalletApi\Database\Type\MoneyTypecast;
use JsonSerializable;
use Money\Currency;
use Money\Money;

class MoneyValue implements ValueInterface, JsonSerializable
{
    private Money $money;

    public function __construct(mixed $amount, string $currency)
    {
        $this->money = new Money(amount: \floor($amount * 100), currency: new Currency(code: $currency));
    }

    public function __call(string $name, array $arguments): mixed
    {
        return call_user_func_array(callback: [$this->money, $name], args: $arguments);
    }

    public function __toString(): string
    {
        return \json_encode(value: $this->money->jsonSerialize());
    }

    public function getAmountReal(): int
    {
        return (int) \floor($this->money->getAmount() / 100);
    }

    public function getCurrency(): string
    {
        return $this->money->getCurrency()->getCode();
    }

    public static function cast(string $value, DatabaseInterface $db): static
    {
        $data = \json_decode(json: $value, associative: true);
        $amount = ($data['amount'] ?? 0) / 100;
        $currency = $data['currency'] ?? MoneyTypecast::DEFAULT_CURRENCY;

        return new self(amount: $amount, currency: $currency);
    }

    public function rawValue(): string
    {
        return $this->__toString();
    }

    public function rawType(): int
    {
        return \PDO::PARAM_STR;
    }

    public function jsonSerialize(): array
    {
        return $this->money->jsonSerialize();
    }
}
