<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange;

use Ifx\Task\Domain\CurrencyExchange\ValueObject\Currency;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\ExchangeRate;

interface ExchangeRateRepositoryInterface
{
    public function findRate(Currency $from, Currency $to): ?ExchangeRate;
}
