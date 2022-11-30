<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../src/BankAccount.php';
require_once __DIR__.'/../../src/command/ViewTransactionsAccountBankCommand.php';

/**
 * Doit afficher la liste de toutes les transactions du compte
 * Doit afficher par transaction : la date, le montant (positif si dépôt, négatif si retrait) et le nouveau solde du compte
 * Doit afficher les transactions de la plus recente a la plus ancienne
 */
class ViewTransactionsAccountBankCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $bankAccount = new BankAccount();
        $bankAccount->makeDeposit(100_000);
        $bankAccount->makeDeposit(150_000);
        $bankAccount->makeWithdrawal(50_000);
        $bankAccount->makeDeposit(10_000);

        $application = new Application();
        $application->add(new ViewTransactionsAccountBankCommand($bankAccount, 'viewTransactions'));
        $command = $application->find('viewTransactions');

        $this->commandTester = new CommandTester($command);
    }

    public function testDisplayHeadersTableTransactions(): void
    {
        $this->commandTester->execute([]);

        $display = trim($this->commandTester->getDisplay());

        $this->assertStringContainsString('Date', $display);
        $this->assertStringContainsString('Transaction', $display);
        $this->assertStringContainsString('Balance', $display);
    }

    public function testDisplayTableTransactionsAmount(): void
    {
        $this->commandTester->execute([]);

        $display = trim($this->commandTester->getDisplay());

        $this->assertStringContainsString('1000€', $display);
        $this->assertStringContainsString('1500€', $display);
        $this->assertStringContainsString('-500€', $display);
        $this->assertStringContainsString('100€', $display);
    }

    public function testDisplayTransactionsDateFormat(): void
    {
        $this->commandTester->execute([]);

        $display = trim($this->commandTester->getDisplay());

        $this->assertMatchesRegularExpression('/[0-9]{2}\/[0-1][0-9]\/[0-9]{4}/', $display);
    }

    public function testDisplayTransactionDateWithMilliSeconds(): void
    {
        $prophet = new Prophecy\Prophet();

        $now = new DateTimeImmutable();

        /** @var Transaction $transaction */
        $transaction = $prophet->prophesize(Transaction::class);
        $transaction->getDate()->willReturn($now);
        $transaction->getType()->willReturn(Transaction::TYPE_DEPOSIT);
        $transaction->getAmount()->willReturn(1000);
        $transaction->getBalanceAfterTransaction()->willReturn(11000);

        /** @var BankAccount $bankAccount */
        $bankAccount = $prophet->prophesize(BankAccount::class);
        $bankAccount->getTransactionsSortByDateDesc()->willReturn([$transaction->reveal()]);

        $application = new Application();
        $application->add(new ViewTransactionsAccountBankCommand($bankAccount->reveal(), 'viewTransactions'));
        $command = $application->find('viewTransactions');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $display = trim($commandTester->getDisplay());

        self::assertStringContainsString($now->format('d/m/Y H:i:s.u'), $display);
    }

    public function testDisplayTransactionsBalance(): void
    {
        $this->commandTester->execute([]);

        $display = trim($this->commandTester->getDisplay());

        $this->assertStringContainsString('11000€', $display);
        $this->assertStringContainsString('12500€', $display);
        $this->assertStringContainsString('12000€', $display);
        $this->assertStringContainsString('12100€', $display);
    }
}