<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__.'/../../src/BankAccount.php';
require_once __DIR__.'/../../src/command/BankCommand.php';
require_once __DIR__.'/../../src/command/MakeTransferCommand.php';
require_once __DIR__.'/../../src/client/HttpBankClient.php';

class MakeTransferCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $bankAccount = new BankAccount(new HttpBankClient());

        $application = new Application();
        $application->add(new MakeTransferCommand($bankAccount, 'makeTransfer'));
        $command = $application->find('makeTransfer');

        $this->commandTester = new CommandTester($command);
    }

    public function testPromptTransferCommandAndExecuteTransfer(): void
    {
        $this->commandTester->setInputs([10_000, FakeBankClient::VALID_IBANS[0]]);
        $this->commandTester->execute([]);

        $display = trim($this->commandTester->getDisplay());

        $this->assertStringContainsString('Quel montant voulez vous envoyer ?', $display);
        $this->assertStringContainsString('Pour quel IBAN ?', $display);
        $this->assertStringContainsString(BankClientInterface::SUCCESS_MESSAGE, $display);
    }

    public function testPromptTransferCommandAndExecuteTransferWithError(): void
    {
        $this->commandTester->setInputs([10_000, 'InvalidIban']);
        $this->commandTester->execute([]);

        $display = trim($this->commandTester->getDisplay());

        $this->assertStringContainsString('Quel montant voulez vous envoyer ?', $display);
        $this->assertStringContainsString('Pour quel IBAN ?', $display);
        $this->assertStringContainsString('Le transfert n\'a pas été réalisé, iban incorrect', $display);
    }
}