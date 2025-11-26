<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class AccountServiceTest extends TestCase
{
    private AccountService $accountService;

    protected function setUp(): void
    {
        $this->accountService = new AccountService();
    }

    public function testCreateAccountForUser(): void
    {
        $user = new User(1, 'Alice');

        $account = $this->accountService->createAccount($user);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($user, $account->getUser());
        $this->assertEquals(0.0, $account->getBalance());
    }

    public function testCreateAccountGeneratesUniqueIds(): void
    {
        $user1 = new User(1, 'Alice');
        $user2 = new User(2, 'Bob');

        $account1 = $this->accountService->createAccount($user1);
        $account2 = $this->accountService->createAccount($user2);

        $this->assertNotEquals($account1->getId(), $account2->getId());
    }

    public function testDepositMoneyToAccount(): void
    {
        $user = new User(1, 'Alice');
        $account = $this->accountService->createAccount($user);
        $amount = 100.0;

        $this->accountService->deposit($account, $amount, 'Allocation mensuelle');

        $this->assertEquals($amount, $account->getBalance());
    }

    public function testDepositNegativeAmountThrowsException(): void
    {
        $user = new User(1, 'Alice');
        $account = $this->accountService->createAccount($user);
        $amount = -50.0;

        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Le montant doit être positif');

        $this->accountService->deposit($account, $amount, 'Dépôt invalide');
    }

    public function testRecordExpenseOnAccount(): void
    {
        $user = new User(1, 'Alice');
        $account = $this->accountService->createAccount($user);
        $this->accountService->deposit($account, 100.0, 'Allocation initiale');
        $expenseAmount = 30.0;

        $this->accountService->recordExpense($account, $expenseAmount, 'Achat de livres');

        $this->assertEquals(70.0, $account->getBalance());
    }

    public function testRecordExpenseExceedingBalanceThrowsException(): void
    {
        $user = new User(1, 'Alice');
        $account = $this->accountService->createAccount($user);
        $this->accountService->deposit($account, 50.0, 'Allocation initiale');
        $expenseAmount = 100.0;

        $this->expectException(InsufficientBalanceException::class);
        $this->expectExceptionMessage('Solde insuffisant');

        $this->accountService->recordExpense($account, $expenseAmount, 'Dépense trop importante');
    }

    public function testCompleteAccountWorkflow(): void
    {
        $user = new User(1, 'Alice');
        $account = $this->accountService->createAccount($user);

        $this->accountService->deposit($account, 200.0, 'Allocation mensuelle');
        $this->accountService->recordExpense($account, 50.0, 'Vêtements');
        $this->accountService->recordExpense($account, 30.0, 'Livres');
        $this->accountService->deposit($account, 50.0, 'Petit boulot');

        $this->assertEquals(170.0, $account->getBalance());
        $this->assertCount(4, $account->getTransactions());
    }

    public function testFindAccountById(): void
    {
        $user = new User(1, 'Alice');
        $createdAccount = $this->accountService->createAccount($user);
        $accountId = $createdAccount->getId();

        $foundAccount = $this->accountService->findAccountById($accountId);

        $this->assertNotNull($foundAccount);
        $this->assertEquals($accountId, $foundAccount->getId());
        $this->assertEquals($createdAccount, $foundAccount);
    }

    public function testFindAccountByIdReturnsNullForNonExistentAccount(): void
    {
        $nonExistentId = 99999;

        $account = $this->accountService->findAccountById($nonExistentId);

        $this->assertNull($account);
    }
}
