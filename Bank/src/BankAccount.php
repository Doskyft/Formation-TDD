<?php

declare(strict_types=1);

class BankAccount {
    private int $balance = 1_000_000;

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function makeDeposit(int $depositAmount): self
    {
        $this->balance += $depositAmount;

        return $this;
    }

    public function makeWithdrawal(int $withdrawalAmount): self
    {
        if ($this->getBalance() < $withdrawalAmount) {
            throw new Exception('Insufficient funds on bank account');
        }

        $this->balance -= $withdrawalAmount;

        return $this;
    }
}