<?php

declare(strict_types=1);

class Transaction {
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAWAL = 'withdrawal';

    private DateTimeInterface $date;

    public function __construct(
        private readonly int $amount,
        private readonly string $type,
        private int $balanceAfterTransaction,
    ) {
        $this->date = new DateTimeImmutable();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getBalanceAfterTransaction(): int
    {
        return $this->balanceAfterTransaction;
    }

    public function setBalanceAfterTransaction(int $balanceAfterTransaction): self
    {
        $this->balanceAfterTransaction = $balanceAfterTransaction;

        return $this;
    }
}