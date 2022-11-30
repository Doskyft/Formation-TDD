<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../src/BankAccount.php';
require_once __DIR__.'/../../src/command/BankCommand.php';
require_once __DIR__.'/../../src/command/WithdrawalCommand.php';
require_once __DIR__.'/../../src/client/HttpBankClient.php';

class WithdrawalCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $bankAccount = new BankAccount(new HttpBankClient());

        $application = new Application();
        $application->add(new WithdrawalCommand($bankAccount, 'withdrawal'));
        $command = $application->find('withdrawal');

        $this->commandTester = new CommandTester($command);
    }

    public function testPromptWithdrawalCommand(): void
    {
        $this->commandTester->execute([]);

        $this->assertStringContainsString('Quel montant voulez vous retirer ?', trim($this->commandTester->getDisplay()));
    }

    public function testNewBankAccountBalanceAfterMakingWithdrawal(): void
    {
        $this->commandTester->setInputs([1000]);
        $this->commandTester->execute([]);
        $this->assertStringContainsString('Le solde de votre compte est de : 9000€', trim($this->commandTester->getDisplay()));
    }

    public function testPromptErrorMessageIfInsufficientFundsForWithdrawal(): void
    {
        $this->commandTester->setInputs([11000]);
        $this->commandTester->execute([]);
        $this->assertStringContainsString('Le solde de votre compte est insuffisant.', trim($this->commandTester->getDisplay()));
    }
}