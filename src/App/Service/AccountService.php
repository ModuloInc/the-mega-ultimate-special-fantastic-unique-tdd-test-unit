<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Account;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\InvalidAmountException;
use App\Domain\User;

class AccountService
{
    private array $accounts = [];

    private int $nextAccountId = 1;

    public function createAccount(User $user): Account
    {
        $account = new Account($this->nextAccountId++, $user);
        $this->accounts[$account->getId()] = $account;

        return $account;
    }

    public function deposit(Account $account, float $amount, string $description): void
    {
        $account->deposit($amount, $description);
    }

    public function recordExpense(Account $account, float $amount, string $description): void
    {
        $account->recordExpense($amount, $description);
    }

    public function findAccountById(int $id): ?Account
    {
        return $this->accounts[$id] ?? null;
    }
}

