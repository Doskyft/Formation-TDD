<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/command/BankCommand.php';
require __DIR__.'/client/HttpBankClient.php';

use Symfony\Component\Console\Application;

$application = new Application();

$bankAccount = new BankAccount(new HttpBankClient());

$application->add(new BankCommand($bankAccount, 'bank'));
$application->add(new DepositCommand($bankAccount, 'deposit'));

$application->run();