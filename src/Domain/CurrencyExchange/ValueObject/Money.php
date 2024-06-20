<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange\ValueObject;

class Money
{
    private string $amount;
    private Currency $currency;

    public function __construct(string $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
