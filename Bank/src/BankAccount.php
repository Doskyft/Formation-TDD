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
}