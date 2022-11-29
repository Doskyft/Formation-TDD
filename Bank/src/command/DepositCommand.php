<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DepositCommand extends Command
{
    public function __construct(private BankAccount $bankAccount, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question("Quel montant voulez vous déposer ?\n", 0);

        $result = $helper->ask($input, $output, $question);

        $this->bankAccount->makeDeposit($result * 100);

        $output->writeln('Le solde de votre compte est de : '.$this->bankAccount->getBalance() / 100 . '€');

        $application = new Application();
        $application->add(new BankCommand($this->bankAccount,'bankAccount'));
        $command = $application->find('bankAccount');
        $command->run($input, $output);

        return Command::SUCCESS;
    }
}



//$application = new Application();
//$application->add(new BankCommand($this->bankAccount,'bankAccount'));
//$command = $application->find('bankAccount');
//$command->run($input, $output);