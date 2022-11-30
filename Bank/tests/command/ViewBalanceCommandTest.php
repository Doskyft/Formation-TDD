<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../src/BankAccount.php';
require_once __DIR__.'/../../src/command/BankCommand.php';
require_once __DIR__.'/../../src/command/ViewBalanceCommand.php';
require_once __DIR__.'/../../src/client/HttpBankClient.php';

/*
 * As a user I can select the deposit option on main screen
 * As a user I can choose the amount of the deposit on my bank account
 * After deposit I should see the new balance of my bank account and be redirected to the main screen
 *
 */

class ViewBalanceCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $bankAccount = new BankAccount(new HttpBankClient());
        $application = new Application();
        $application->add(new ViewBalanceCommand($bankAccount,'viewAccountBalance'));
        $command = $application->find('viewAccountBalance');

        $this->commandTester = new CommandTester($command);
    }

    public function testShowAccountBalance(): void
    {
        $this->commandTester->execute([]);

        $this->assertStringContainsString('Le solde de votre compte est de : 10000€', trim($this->commandTester->getDisplay()));
    }
}