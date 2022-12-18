<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Type;

use Attribute;
use Cycle\ORM\Entity\Behavior\Schema\BaseModifier;
use GTAWalletApi\Database\Type\Listener\MoneyListener;
use JetBrains\PhpStorm\ArrayShape;
use Spiral\Attributes\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor()
 * @Target({"CLASS"})
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS), NamedArgumentConstructor]
final class MoneyTypecast extends BaseModifier
{
    public const DEFAULT_CURRENCY = 'RUB';
    public const CURRENCY_CREDITS = 'CRED';

    /**
     * @param non-empty-string $field Money property name
     */
    public function __construct(
        private readonly string $field = 'amount'
    ) {
    }

    protected function getListenerClass(): string
    {
        return MoneyListener::class;
    }

    #[ArrayShape(['field' => 'string'])]
    protected function getListenerArgs(): array
    {
        return [
            'field' => $this->field,
        ];
    }
}
