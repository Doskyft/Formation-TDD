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
}