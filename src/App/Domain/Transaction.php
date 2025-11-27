<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use InvalidArgumentException;

class Transaction
{
    public const string TYPE_DEPOSIT = 'deposit';
    public const string TYPE_EXPENSE = 'expense';
    public const string TYPE_ALLOWANCE = 'allowance';

    private readonly DateTimeImmutable $date;

    public function __construct(
        private readonly string $type,
        private readonly float $amount,
        private readonly string $description
    ) {
        $validTypes = [self::TYPE_DEPOSIT, self::TYPE_EXPENSE, self::TYPE_ALLOWANCE];
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException('Type de transaction invalide');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('Le montant doit être positif');
        }

        $this->date = new DateTimeImmutable();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}

