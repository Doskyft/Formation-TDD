<?php

declare(strict_types=1);

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require_once __DIR__.'/BankClientInterface.php';

class HttpBankClient implements BankClientInterface
{
    public function transfer(string $iban, int $amount): string
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'https://bank-kata-tdd.herokuapp.com/transfer', [
            'body' => [
                'ibanFrom' => 'Test',
                'ibanTo' => $iban,
                'amount' => $amount,
            ]
        ]);

        try {
            $statusCode = $response->getStatusCode();

            if (200 === $statusCode) {
                return self::SUCCESS_MESSAGE;
            }

            $content = $response->getContent(false);

            $contentDecoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            $message = $contentDecoded['message'];

            if (
                str_starts_with($message, 'The account with IBAN') &&
                str_ends_with($message, 'is not an account registred in our bank')
            ) {
                return self::INVALID_IBAN_MESSAGE;
            }

            return $message;
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|JsonException $e) {
            return $e->getMessage();
        }
    }
}