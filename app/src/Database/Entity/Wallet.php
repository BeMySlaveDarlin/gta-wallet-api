<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use GTAWalletApi\Database\Repository\WalletRepository;

#[Entity(role: 'wallet', repository: WalletRepository::class, table: 'money')]
class Wallet
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'integer', name: 'accountId')]
    public int $accountId;

    #[Column(type: 'integer')]
    public int $cash;

    #[Column(type: 'integer', name: 'bankAccount')]
    public int $bankAccount;

    #[Column(type: 'integer', name: 'bankAmount')]
    public int $bankAmount;

    #[Column(type: 'integer')]
    public int $credits;
}
