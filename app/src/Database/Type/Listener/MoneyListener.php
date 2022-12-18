<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Type\Listener;

use Cycle\ORM\Entity\Behavior\Attribute\Listen;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnUpdate;
use GTAWalletApi\Database\Type\MoneyTypecast;
use GTAWalletApi\Helper\MoneyValue;

final class MoneyListener
{
    public function __construct(
        private readonly string $field = 'amount'
    ) {
    }

    #[Listen(OnCreate::class)]
    #[Listen(OnUpdate::class)]
    public function __invoke(OnCreate | OnUpdate $event): void
    {
        $data = $event->state->getData();
        $amount = $data[$this->field] ?? 0;
        $money = $amount instanceof MoneyValue
            ? $amount
            : new MoneyValue(
                amount: \floor($amount * 100),
                currency: MoneyTypecast::DEFAULT_CURRENCY
            );

        $event->state->register(key: $this->field, value: $money);
    }
}
