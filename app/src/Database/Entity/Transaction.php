<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use GTAWalletApi\Database\Repository\TransactionsRepository;

#[Entity(role: 'transaction', repository: TransactionsRepository::class, table: 'moneylog')]
#[CreatedAt(field: 'createdAt')]
class Transaction
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'integer', name: 'accountId')]
    public int $accountId;

    #[Column(type: 'integer')]
    public int $operation;

    #[Column(type: 'integer')]
    public int $amount;

    #[Column(type: 'integer', name: 'entityId')]
    public int $entityId;

    #[Column(type: 'datetime', name: 'createdAt')]
    public \DateTimeImmutable $createdAt;

    #[Column(type: 'string')]
    public string $description;
}
