<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private Teenager $teenager;

    protected function setUp(): void
    {
        $this->teenager = new Teenager(1, 'Alice');
    }

    public function testCreateAccountWithZeroBalance(): void
    {
        $account = new Account(1, $this->teenager);

        $this->assertEquals(1, $account->getId());
        $this->assertEquals($this->teenager, $account->getTeenager());
        $this->assertEquals(0.0, $account->getBalance());
        $this->assertEmpty($account->getTransactions());
    }

    public function testDepositIncreasesBalance(): void
    {
        $account = new Account(1, $this->teenager);
        $amount = 50.0;

        $account->deposit($amount, 'Allocation initiale');

        $this->assertEquals(50.0, $account->getBalance());
        $this->assertCount(1, $account->getTransactions());
        $transaction = $account->getTransactions()[0];
        $this->assertEquals(Transaction::TYPE_DEPOSIT, $transaction->getType());
        $this->assertEquals($amount, $transaction->getAmount());
    }

    public function testDepositNegativeAmountThrowsException(): void
    {
        $account = new Account(1, $this->teenager);
        $amount = -10.0;

        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Le montant doit être positif');

        $account->deposit($amount, 'Dépôt invalide');
    }

    public function testDepositZeroAmountThrowsException(): void
    {
        $account = new Account(1, $this->teenager);
        $amount = 0.0;

        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Le montant doit être positif');

        $account->deposit($amount, 'Dépôt invalide');
    }

    public function testRecordExpenseDecreasesBalance(): void
    {
        $account = new Account(1, $this->teenager);
        $account->deposit(100.0, 'Allocation initiale');
        $expenseAmount = 30.0;

        $account->recordExpense($expenseAmount, 'Achat de livres');

        $this->assertEquals(70.0, $account->getBalance());
        $this->assertCount(2, $account->getTransactions());
        $expense = $account->getTransactions()[1];
        $this->assertEquals(Transaction::TYPE_EXPENSE, $expense->getType());
        $this->assertEquals($expenseAmount, $expense->getAmount());
    }

    public function testRecordExpenseExceedingBalanceThrowsException(): void
    {
        $account = new Account(1, $this->teenager);
        $account->deposit(50.0, 'Allocation initiale');
        $expenseAmount = 100.0;

        $this->expectException(InsufficientBalanceException::class);
        $this->expectExceptionMessage('Solde insuffisant');

        $account->recordExpense($expenseAmount, 'Dépense trop importante');
    }

    public function testRecordExpenseWithZeroAmountThrowsException(): void
    {
        $account = new Account(1, $this->teenager);
        $account->deposit(50.0, 'Allocation initiale');

        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Le montant doit être positif');

        $account->recordExpense(0.0, 'Dépense invalide');
    }

    public function testMultipleTransactions(): void
    {
        $account = new Account(1, $this->teenager);

        $account->deposit(100.0, 'Allocation initiale');
        $account->deposit(50.0, 'Argent supplémentaire');
        $account->recordExpense(30.0, 'Achat de livres');
        $account->recordExpense(20.0, 'Sortie cinéma');
        $account->deposit(25.0, 'Petit boulot');

        $this->assertEquals(125.0, $account->getBalance());
        $this->assertCount(5, $account->getTransactions());
    }

    public function testRecordExpenseEqualToOneHundredPercentOfBalance(): void
    {
        $account = new Account(1, $this->teenager);
        $account->deposit(50.0, 'Allocation initiale');
        $expenseAmount = 50.0;

        $account->recordExpense($expenseAmount, 'Dépense totale');

        $this->assertEquals(0.0, $account->getBalance());
    }

    public function testTransactionHistoryIsRecorded(): void
    {
        $account = new Account(1, $this->teenager);

        $account->deposit(100.0, 'Premier dépôt');
        $account->recordExpense(30.0, 'Première dépense');

        $transactions = $account->getTransactions();
        $this->assertCount(2, $transactions);

        $deposit = $transactions[0];
        $this->assertEquals(Transaction::TYPE_DEPOSIT, $deposit->getType());
        $this->assertEquals(100.0, $deposit->getAmount());
        $this->assertEquals('Premier dépôt', $deposit->getDescription());
        $this->assertNotNull($deposit->getDate());

        $expense = $transactions[1];
        $this->assertEquals(Transaction::TYPE_EXPENSE, $expense->getType());
        $this->assertEquals(30.0, $expense->getAmount());
        $this->assertEquals('Première dépense', $expense->getDescription());
    }
}

