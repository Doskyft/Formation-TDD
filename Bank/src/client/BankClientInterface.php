<?php

interface BankClientInterface
{
    public const SUCCESS_MESSAGE = 'Le transfert a bien été réalisé';
    public const INVALID_IBAN_MESSAGE = 'Le transfert n\'a pas été réalisé, iban incorrect';
    public const INVALID_AMOUNT_MESSAGE = 'Le transfert n\'a pas été réalisé, montant incorrect';

    public function transfer(string $iban, int $amount): string;
}