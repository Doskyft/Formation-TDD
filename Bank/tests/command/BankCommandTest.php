<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../src/BankAccount.php';
require_once __DIR__.'/../../src/command/BankCommand.php';

/*
 * As a user I can select the deposit option on main screen
 * As a user I can choose the amount of the deposit on my bank account
 * After deposit I should see the new balance of my bank account and be redirected to the main screen
 *
 */

class BankCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private Command $command;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new BankCommand(new BankAccount(), 'bank'));
        $this->command = $application->find('bank');

        $this->commandTester = new CommandTester($this->command);
    }

    public function testUserCanViewOptionsOnMainScreen(): void
    {
        $this->commandTester->execute([]);

        $display = $this->commandTester->getDisplay();

        $this->assertStringContainsString("Que voulez vous faire sur votre compte ?", $display);
        $this->assertStringContainsString("Voir le solde du compte", $display);
        $this->assertStringContainsString("Faire un dépôt", $display);
    }
}