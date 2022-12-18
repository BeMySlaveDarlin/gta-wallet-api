<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Repository;

use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Select\Repository;

#[Index(columns: ['uuid'], unique: true)]
#[Index(columns: ['accountId'])]
#[Index(columns: ['paymentSystem'])]
#[Index(columns: ['extId'])]
#[Index(columns: ['status'])]
#[Index(columns: ['commissionType'])]
#[Index(columns: ['createdAt'])]
class CreditRequestsRepository extends Repository
{
}
