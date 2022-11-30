<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class WithdrawalCommand extends Command
{
    public function __construct(private BankAccount $bankAccount, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question("Quel montant voulez vous retirer ?\n", 0);

        $result = $helper->ask($input, $output, $question);

        try {
            $this->bankAccount->makeWithdrawal($result * 100);
        } catch (Exception) {
            $output->writeln('Le solde de votre compte est insuffisant.');
            $this->retryWithdrawal($input, $output);
        }

        $output->writeln('Le solde de votre compte est de : '.$this->bankAccount->getBalance() / 100 . '€');

        return Command::SUCCESS;
    }

    private function retryWithdrawal(InputInterface $input, OutputInterface $output): void
    {
        $application = new Application();
        $application->add(new WithdrawalCommand($this->bankAccount, 'withdrawal'));
        $command = $application->find('withdrawal');
        $command->run($input, $output);
    }
}