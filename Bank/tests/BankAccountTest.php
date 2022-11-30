<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../src/BankAccount.php';

class BankAccountTest extends TestCase
{
    public function testFetchBankAccountBalance(): void
    {
        $bankAccount = new BankAccount();
        self::assertSame(1_000_000, $bankAccount->getBalance());
    }

    public function testMakingDepositOnBankAccount(): void
    {
        $bankAccount = new BankAccount();
        $bankAccount->makeDeposit(100_000);

        self::assertSame(1_100_000, $bankAccount->getBalance());
    }

    public function testMakingWithdrawalFromBankAccount(): void
    {
        $bankAccount = new BankAccount();
        $bankAccount->makeWithdrawal(100_000);

        self::assertSame(900_000, $bankAccount->getBalance());
    }

    public function testCannotMakeWithdrawalIfInsufficientFundsOnBankAccount(): void
    {
        $bankAccount = new BankAccount();
        self::expectExceptionMessage('Insufficient funds on bank account');
        $bankAccount->makeWithdrawal(11_000_000);
    }

    public function testBankAccountCanRegisterANewDepositTransaction(): void
    {
        $bankAccount = new BankAccount();

        $bankAccount->makeDeposit(100_000);

        $transaction = $bankAccount->getTransactions()[0];

        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertSame(100_000, $transaction->getAmount());
        self::assertInstanceOf(DateTimeInterface::class, $transaction->getDate());
        self::assertSame(Transaction::TYPE_DEPOSIT, $transaction->getType());
        self::assertSame(1_100_000, $transaction->getBalanceAfterTransaction());
    }

    public function testBankAccountCanRegisterANewWithdrawalTransaction(): void
    {
        $bankAccount = new BankAccount();

        $bankAccount->makeWithdrawal(100_000);

        $transaction = $bankAccount->getTransactions()[0];

        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertSame(100_000, $transaction->getAmount());
        self::assertSame(Transaction::TYPE_WITHDRAWAL, $transaction->getType());
        self::assertSame(900_000, $transaction->getBalanceAfterTransaction());
    }

    public function testBankAccountCanRegisterManyTransactions(): void
    {
        $bankAccount = new BankAccount();

        $bankAccount->makeWithdrawal(100_000);
        $bankAccount->makeWithdrawal(100_000);
        $bankAccount->makeWithdrawal(100_000);
        $bankAccount->makeDeposit(100_000);
        $bankAccount->makeDeposit(100_000);

        self::assertCount(5, $bankAccount->getTransactions());
    }

    public function testBankAccountGetTransactionsSortByDateDesc(): void
    {
        $bankAccount = new BankAccount();

        $bankAccount->makeDeposit(100_000);
        $bankAccount->makeWithdrawal(100_000);

        $transactions = $bankAccount->getTransactionsSortByDateDesc();

        self::assertSame(Transaction::TYPE_WITHDRAWAL, $transactions[0]->getType());
        self::assertSame(Transaction::TYPE_DEPOSIT, $transactions[1]->getType());
    }
}