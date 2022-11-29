<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../src/BankAccount.php';
require_once __DIR__.'/../../src/command/BankCommand.php';
require_once __DIR__.'/../../src/command/DepositCommand.php';

/*
 * As a user I can select the deposit option on main screen
 * As a user I can choose the amount of the deposit on my bank account
 * After deposit I should see the new balance of my bank account and be redirected to the main screen
 *
 */

class DepositCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $bankAccount = new BankAccount();

        $application = new Application();
        $application->add(new DepositCommand($bankAccount, 'deposit'));
        $command = $application->find('deposit');

        $this->commandTester = new CommandTester($command);
    }

    public function testPromptDepositCommand(): void
    {
        $this->commandTester->execute([]);

        $this->assertStringContainsString('Quel montant voulez vous déposer ?', trim($this->commandTester->getDisplay()));
    }

    public function testNewBankAccountBalanceAfterMakingDeposit(): void
    {
        $this->commandTester->setInputs([1000]);
        $this->commandTester->execute([]);
        $this->assertStringContainsString('Le solde de votre compte est de : 11000€', trim($this->commandTester->getDisplay()));
    }
}