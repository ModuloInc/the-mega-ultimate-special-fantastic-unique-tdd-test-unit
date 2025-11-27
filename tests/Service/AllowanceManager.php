<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Account;
use App\Domain\Exception\InvalidAmountException;
use App\Domain\Transaction;
use InvalidArgumentException;
use SplObjectStorage;

class AllowanceManager
{
    private SplObjectStorage $weeklyAllowances;

    public function __construct()
    {
        $this->weeklyAllowances = new SplObjectStorage();
    }

    public function setWeeklyAllowance(Account $account, float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidAmountException('Le montant doit être positif');
        }

        $this->weeklyAllowances[$account] = $amount;
    }

    public function getWeeklyAllowance(Account $account): ?float
    {
        if (!isset($this->weeklyAllowances[$account])) {
            return null;
        }

        return $this->weeklyAllowances[$account];
    }

    public function removeWeeklyAllowance(Account $account): void
    {
        if (isset($this->weeklyAllowances[$account])) {
            unset($this->weeklyAllowances[$account]);
        }
    }

    /**
     * Traite l'allocation hebdomadaire pour un compte.
     *
     * @throws InvalidArgumentException Si aucune allocation n'est configurée
     */
    public function processWeeklyAllowance(Account $account): void
    {
        if (!isset($this->weeklyAllowances[$account])) {
            throw new InvalidArgumentException('Aucune allocation hebdomadaire configurée pour ce compte');
        }

        $amount = $this->weeklyAllowances[$account];
        $account->addAllowance($amount, 'Allocation hebdomadaire automatique');
    }
}

