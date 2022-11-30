<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ViewTransactionsAccountBankCommand extends Command
{
    public function __construct(private readonly BankAccount $bankAccount, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Date', 'Transaction', 'Balance'])
            ->setRows(array_map(static fn (Transaction $transaction) => [
                $transaction->getDate()->format('d/m/Y H:i:s.u'),
                (Transaction::TYPE_WITHDRAWAL === $transaction->getType() ? '-' : '') . $transaction->getAmount() / 100 . '€',
                $transaction->getBalanceAfterTransaction() / 100 . '€',
            ], $this->bankAccount->getTransactionsSortByDateDesc()))
        ;

        $table->render();

        return Command::SUCCESS;
    }
}