<?php

declare(strict_types=1);

namespace GTAWalletApi\Database\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;
use GTAWalletApi\Database\Repository\CreditRequestsRepository;
use GTAWalletApi\Database\Type\MoneyTypecast;
use GTAWalletApi\Helper\MoneyValue;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

#[Entity(role: 'credit_request', repository: CreditRequestsRepository::class, table: 'moneycreditrequests')]
#[Uuid4]
#[MoneyTypecast(field: 'credits')]
#[MoneyTypecast(field: 'amount')]
#[MoneyTypecast(field: 'amountFinal')]
#[MoneyTypecast(field: 'commission')]
#[CreatedAt(field: 'createdAt')]
class CreditRequest implements JsonSerializable
{
    public const STATUS_NEW = 'new';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';

    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'uuid')]
    public UuidInterface $uuid;

    #[Column(type: 'integer', name: 'accountId')]
    public int $accountId;

    #[Column(type: 'string', name: 'paymentSystem')]
    public string $paymentSystem;

    #[Column(type: 'string', name: 'extId', nullable: true)]
    public ?string $extId;

    #[Column(type: 'enum(new,success,fail)', default: 'new')]
    public string $status;

    #[Column(type: 'float', typecast: [MoneyValue::class, 'cast'])]
    public MoneyValue $credits;

    #[Column(type: 'string', typecast: [MoneyValue::class, 'cast'])]
    public MoneyValue $amount;

    #[Column(type: 'string', name: 'amountFinal', typecast: [MoneyValue::class, 'cast'])]
    public MoneyValue $amountFinal;

    #[Column(type: 'string', typecast: [MoneyValue::class, 'cast'])]
    public MoneyValue $commission;

    #[Column(type: 'string', name: 'commissionType', nullable: true)]
    public ?string $commissionType;

    #[Column(type: 'text', nullable: true)]
    public ?string $request;

    #[Column(type: 'text', nullable: true)]
    public ?string $callback;

    #[Column(type: 'text', nullable: true)]
    public ?string $description;

    #[Column(type: 'datetime', name: 'createdAt')]
    public \DateTimeImmutable $createdAt;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'accountId' => $this->accountId,
            'paymentSystem' => $this->paymentSystem,
            'extId' => $this->extId,
            'status' => $this->status,
            'credits' => $this->credits,
            'amount' => $this->amount,
            'amountFinal' => $this->amountFinal,
            'commission' => $this->commission,
            'commissionType' => $this->commissionType,
            'description' => 'Secret RP credits',
            'createdAt' => $this->createdAt,
        ];
    }
}
