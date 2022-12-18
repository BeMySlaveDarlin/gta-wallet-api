<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Repository;

use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;

#[Index(columns: ['accountId'])]
#[Index(columns: ['operation'])]
#[Index(columns: ['entityId'])]
class TransactionsRepository extends Repository
{
}
