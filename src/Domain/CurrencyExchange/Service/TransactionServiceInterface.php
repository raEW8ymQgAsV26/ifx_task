<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange\Service;

use Ifx\Task\Domain\CurrencyExchange\ValueObject\Currency;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\Money;

interface TransactionServiceInterface
{
    public function executeExchange(Money $money, Currency $toCurrency): Money;
}
