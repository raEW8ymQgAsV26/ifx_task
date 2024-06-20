<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange\ValueObject;

class ExchangeRate
{
    private Currency $from;
    private Currency $to;
    private string $rate;

    public function __construct(Currency $from, Currency $to, string $rate)
    {
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
    }

    public function getFrom(): Currency
    {
        return $this->from;
    }

    public function getTo(): Currency
    {
        return $this->to;
    }

    public function getRate(): string
    {
        return $this->rate;
    }
}
