<?php

declare(strict_types= 1);

namespace Ifx\Task\Infrastructure\CurrencyExchange;

use Ifx\Task\Domain\CurrencyExchange\ValueObject\Currency;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\ExchangeRate;
use Ifx\Task\Domain\CurrencyExchange\ExchangeRateRepositoryInterface;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    private array $exchangeRates;

    public function __construct(array $exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
    }

    public function findRate(Currency $from, Currency $to): ?ExchangeRate
    {
        foreach ($this->exchangeRates as $exchangeRate) {
          if ($exchangeRate->getFrom()->getCode() === $from->getCode() 
              && $exchangeRate->getTo()->getCode() === $to->getCode()) {
              return $exchangeRate;
          }
        }

        return null;
    }
}
