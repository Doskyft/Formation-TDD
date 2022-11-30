<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ViewBalanceCommand extends Command
{
    public function __construct(private BankAccount $bankAccount, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Le solde de votre compte est de : '. ($this->bankAccount->getBalance() / 100). '€');

        return Command::SUCCESS;
    }
}