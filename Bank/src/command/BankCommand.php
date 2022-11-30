<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

require_once __DIR__.'/ViewBalanceCommand.php';
require_once __DIR__.'/../BankAccount.php';
require_once __DIR__.'/DepositCommand.php';
require_once __DIR__.'/WithdrawalCommand.php';
require_once __DIR__.'/ViewTransactionsAccountBankCommand.php';

class BankCommand extends Command
{
    public function __construct(private BankAccount $bankAccount, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        do {
            $question = new ChoiceQuestion(
                'Que voulez vous faire sur votre compte ?',
                [
                    'view' => 'Voir le solde du compte',
                    'deposit' => 'Faire un dépôt',
                    'withdrawal' => 'Effectuer un retrait',
                    'listTx' => 'Afficher vos transactions',
                    'quit' => 'Quitter'
                ],
                'quit'
            );

            $result = $helper->ask($input, $output, $question);
            $application = new Application();

            if ($result === 'view') {
                $application->add(new ViewBalanceCommand($this->bankAccount, 'viewAccountBank'));
                $command = $application->find('viewAccountBank');
                $command->run($input, $output);
            } elseif ($result === 'deposit') {
                $application->add(new DepositCommand($this->bankAccount, 'deposit'));
                $command = $application->find('deposit');
                $command->run($input, $output);
            } elseif ($result === 'withdrawal') {
                $application->add(new WithdrawalCommand($this->bankAccount, 'withdrawal'));
                $command = $application->find('withdrawal');
                $command->run($input, $output);
            }elseif ($result==='listTx') {
                $application->add(new ViewTransactionsAccountBankCommand($this->bankAccount, 'viewTransactions'));
                $command = $application->find('viewTransactions');
                $command->run($input, $output);
            }
        } while ($result !== 'quit');


        return Command::SUCCESS;
    }
}

