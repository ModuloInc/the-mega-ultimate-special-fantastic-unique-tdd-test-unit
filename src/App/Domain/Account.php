<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\InvalidAmountException;

class Account
{
    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    private float $balance = 0.0;

    public function __construct(
        private readonly int $id,
        private readonly User $user
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function deposit(float $amount, string $description): void
    {
        $this->validatePositiveAmount($amount);

        $this->balance += $amount;
        $this->transactions[] = new Transaction(Transaction::TYPE_DEPOSIT, $amount, $description);
    }

    public function recordExpense(float $amount, string $description): void
    {
        $this->validatePositiveAmount($amount);

        if ($this->balance < $amount) {
            throw new InsufficientBalanceException('Solde insuffisant');
        }

        $this->balance -= $amount;
        $this->transactions[] = new Transaction(Transaction::TYPE_EXPENSE, $amount, $description);
    }

    public function addAllowance(float $amount, string $description): void
    {
        $this->validatePositiveAmount($amount);

        $this->balance += $amount;
        $this->transactions[] = new Transaction(Transaction::TYPE_ALLOWANCE, $amount, $description);
    }

    private function validatePositiveAmount(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidAmountException('Le montant doit être positif');
        }
    }
}

