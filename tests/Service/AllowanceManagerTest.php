<?php

declare(strict_types=1);

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Domain\User;
use App\Domain\Account;
use App\Service\AllowanceManager;
use App\Domain\Exception\InvalidAmountException;
use InvalidArgumentException;

class AllowanceManagerTest extends TestCase
{
    private AllowanceManager $allowanceManager;

    protected function setUp(): void
    {
        $this->allowanceManager = new AllowanceManager();
    }

    public function testSetWeeklyAllowance(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $weeklyAmount = 50.0;

        $this->allowanceManager->setWeeklyAllowance($account, $weeklyAmount);

        $this->assertEquals($weeklyAmount, $this->allowanceManager->getWeeklyAllowance($account));
    }

    public function testUpdateWeeklyAllowance(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $initialAmount = 50.0;
        $newAmount = 75.0;

        $this->allowanceManager->setWeeklyAllowance($account, $initialAmount);
        $this->allowanceManager->setWeeklyAllowance($account, $newAmount);

        $this->assertEquals($newAmount, $this->allowanceManager->getWeeklyAllowance($account));
    }

    public function testGetWeeklyAllowanceForAccountWithoutAllowanceReturnsNull(): void
    {
        $account = new Account(1, new User(1, 'Alice'));

        $result = $this->allowanceManager->getWeeklyAllowance($account);

        $this->assertNull($result);
    }

    public function testSetNegativeWeeklyAllowanceThrowsException(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $negativeAmount = -10.0;

        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Le montant doit être positif');

        $this->allowanceManager->setWeeklyAllowance($account, $negativeAmount);
    }

    public function testSetZeroWeeklyAllowanceThrowsException(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $zeroAmount = 0.0;

        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Le montant doit être positif');

        $this->allowanceManager->setWeeklyAllowance($account, $zeroAmount);
    }

    public function testRemoveWeeklyAllowance(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $this->allowanceManager->setWeeklyAllowance($account, 50.0);

        $this->allowanceManager->removeWeeklyAllowance($account);

        $this->assertNull($this->allowanceManager->getWeeklyAllowance($account));
    }

    public function testProcessWeeklyAllowance(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $weeklyAmount = 50.0;
        $this->allowanceManager->setWeeklyAllowance($account, $weeklyAmount);

        $this->allowanceManager->processWeeklyAllowance($account);

        $this->assertEquals($weeklyAmount, $account->getBalance());
        $transactions = $account->getTransactions();
        $this->assertCount(1, $transactions);
        $transaction = $transactions[0];
        $this->assertEquals(\App\Domain\Transaction::TYPE_ALLOWANCE, $transaction->getType());
        $this->assertEquals($weeklyAmount, $transaction->getAmount());
    }

    public function testProcessWeeklyAllowanceMultipleTimes(): void
    {
        $account = new Account(1, new User(1, 'Alice'));
        $weeklyAmount = 50.0;
        $this->allowanceManager->setWeeklyAllowance($account, $weeklyAmount);

        $this->allowanceManager->processWeeklyAllowance($account);
        $this->allowanceManager->processWeeklyAllowance($account);
        $this->allowanceManager->processWeeklyAllowance($account);

        $this->assertEquals(150.0, $account->getBalance());
        $this->assertCount(3, $account->getTransactions());
    }

    public function testProcessWeeklyAllowanceWithoutConfigurationThrowsException(): void
    {
        $account = new Account(1, new User(1, 'Alice'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Aucune allocation hebdomadaire configurée pour ce compte');

        $this->allowanceManager->processWeeklyAllowance($account);
    }

    public function testMultipleAccountsWithDifferentAllowances(): void
    {
        $account1 = new Account(1, new User(1, 'Alice'));
        $account2 = new Account(2, new User(2, 'Bob'));
        $allowance1 = 50.0;
        $allowance2 = 75.0;

        $this->allowanceManager->setWeeklyAllowance($account1, $allowance1);
        $this->allowanceManager->setWeeklyAllowance($account2, $allowance2);

        $this->assertEquals($allowance1, $this->allowanceManager->getWeeklyAllowance($account1));
        $this->assertEquals($allowance2, $this->allowanceManager->getWeeklyAllowance($account2));
    }
}
