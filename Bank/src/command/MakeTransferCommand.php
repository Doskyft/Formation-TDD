<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeTransferCommand extends Command
{
    public function __construct(private readonly BankAccount $bankAccount, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question("Quel montant voulez vous envoyer ?\n", 0);

        $amount = $helper->ask($input, $output, $question);

        $question = new Question("Pour quel IBAN ?\n", 0);

        $iban = $helper->ask($input, $output, $question);

        $output->writeln($this->bankAccount->makeTransfer($iban, (int) $amount));

        return Command::SUCCESS;
    }
}