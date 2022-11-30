<?php

declare(strict_types=1);

require_once __DIR__.'/../../src/client/BankClientInterface.php';

class FakeBankClient implements BankClientInterface {

    public const VALID_IBANS = [
        'FR7630006000011234567890189',
        'FO9264600123456789',
        'GB33BUKB20201555555555',
    ];

    public function transfer(string $iban, int $amount): string
    {
        if (!in_array($iban, self::VALID_IBANS)) {
            return BankClientInterface::INVALID_IBAN_MESSAGE;
        }

        if ($amount <= 0) {
            return BankClientInterface::INVALID_AMOUNT_MESSAGE;
        }

        return BankClientInterface::SUCCESS_MESSAGE;
    }
}