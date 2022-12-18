<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Repository;

use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;

#[Index(columns: ['accountId'])]
class WalletRepository extends Repository
{
}
