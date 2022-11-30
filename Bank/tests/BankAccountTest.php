<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/BankAccount.php';
require_once __DIR__ . '/Client/FakeBankClient.php';
require_once __DIR__ . '/../src/client/BankClientInterface.php';

/**
 * I receive httpOk (200) response when sending request with correct Iban and positive amount
 * I receive a 403 response when sending request with wrong recipient Iban
 * I receive a 400 response if a fields is missing
 * I receive a 400 response and <<"amount" must be greater than or equal to 0>> in message when sending request with a
 * negative amount
 *
 */
class BankAccountTest extends TestCase
{
    public function testFetchBankAccountBalance(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        self::assertSame(1_000_000, $bankAccount->getBalance());
    }

    public function testMakingDepositOnBankAccount(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        $bankAccount->makeDeposit(100_000);

        self::assertSame(1_100_000, $bankAccount->getBalance());
    }

    public function testMakingWithdrawalFromBankAccount(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        $bankAccount->makeWithdrawal(100_000);

        self::assertSame(900_000, $bankAccount->getBalance());
    }

    public function testCannotMakeWithdrawalIfInsufficientFundsOnBankAccount(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        self::expectExceptionMessage('Insufficient funds on bank account');
        $bankAccount->makeWithdrawal(11_000_000);
    }

    public function testBankAccountCanRegisterANewDepositTransaction(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());

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
        $bankAccount = new BankAccount(new FakeBankClient());

        $bankAccount->makeWithdrawal(100_000);

        $transaction = $bankAccount->getTransactions()[0];

        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertSame(100_000, $transaction->getAmount());
        self::assertSame(Transaction::TYPE_WITHDRAWAL, $transaction->getType());
        self::assertSame(900_000, $transaction->getBalanceAfterTransaction());
    }

    public function testBankAccountCanRegisterManyTransactions(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());

        $bankAccount->makeWithdrawal(100_000);
        $bankAccount->makeWithdrawal(100_000);
        $bankAccount->makeWithdrawal(100_000);
        $bankAccount->makeDeposit(100_000);
        $bankAccount->makeDeposit(100_000);

        self::assertCount(5, $bankAccount->getTransactions());
    }

    public function testBankAccountGetTransactionsSortByDateDesc(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());

        $bankAccount->makeDeposit(100_000);
        $bankAccount->makeWithdrawal(100_000);

        $transactions = $bankAccount->getTransactionsSortByDateDesc();

        self::assertSame(Transaction::TYPE_WITHDRAWAL, $transactions[0]->getType());
        self::assertSame(Transaction::TYPE_DEPOSIT, $transactions[1]->getType());
    }

    public function testBankTransferWillReturnSuccessMessageAndUpdateBalance(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        $response = $bankAccount->makeTransfer(
            FakeBankClient::VALID_IBANS[0],
            10_000
        );

        self::assertSame(990_000, $bankAccount->getBalance());
        self::assertSame(BankClientInterface::SUCCESS_MESSAGE, $response);
    }

    public function testBankTransferWithWrongIbanWillReturnErrorMessage(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        $response = $bankAccount->makeTransfer(
            '1234',
            1000
        );

        self::assertSame(BankClientInterface::INVALID_IBAN_MESSAGE, $response);
    }

    public function testBankTransferWithWrongAmountWillReturnErrorMessage(): void
    {
        $bankAccount = new BankAccount(new FakeBankClient());
        $response = $bankAccount->makeTransfer(
            FakeBankClient::VALID_IBANS[0],
            -1000
        );

        self::assertSame(BankClientInterface::INVALID_AMOUNT_MESSAGE, $response);
    }
}