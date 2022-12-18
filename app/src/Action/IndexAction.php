<?php

declare(strict_types=1);

namespace GTAWalletApi\Action;

use GTAWalletApi\Components\Application\AbstractAction;

class IndexAction extends AbstractAction
{
    public function handle(): string
    {
        return 'Ok!';
    }
}
