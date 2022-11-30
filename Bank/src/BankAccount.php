<?php

declare(strict_types=1);

require_once __DIR__.'/Transaction.php';

class BankAccount {
    public const ACCOUNT_IBAN = 'FR765421321231654143211';
    private int $balance = 1_000_000;

    /** @var array<Transaction> */
    private array $transactions = [];

    public function __construct(BankClientInterface $bankTransferClient)
    {
        $this->bankTransferClient = $bankTransferClient;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function makeDeposit(int $depositAmount): self
    {
        $this->balance += $depositAmount;

        $this->addTransaction(
            new Transaction(
                $depositAmount,
                Transaction::TYPE_DEPOSIT,
                $this->balance
            )
        );

        return $this;
    }

    public function makeTransfer(string $iban, int $amount): string
    {
        $response = $this->bankTransferClient->transfer($iban, $amount);

        if ($response === 'Le transfert a bien été réalisé') {
            $this->balance -= $amount;
        }

        return $response;
    }

    public function fetchAllTransfers(): array
    {
        return [new Transfer(), new Transfer()];
    }

    /**
     * @throws Exception
     */
    public function makeWithdrawal(int $withdrawalAmount): self
    {
        if ($this->getBalance() < $withdrawalAmount) {
            throw new Exception('Insufficient funds on bank account');
        }

        $this->balance -= $withdrawalAmount;

        $this->addTransaction(
            new Transaction(
                $withdrawalAmount,
                Transaction::TYPE_WITHDRAWAL,
                $this->balance
            )
        );

        return $this;
    }

    private function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;

    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getTransactionsSortByDateDesc(): array
    {
        $transactions = $this->getTransactions();

        usort($transactions, static function (Transaction $transactionA, Transaction $transactionB) {
            if ($transactionA->getDate() > $transactionB->getDate()) {
                return -1;
            }

            return 1;
        });

        return $transactions;
    }
}