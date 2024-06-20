<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange\ValueObject;

class Currency
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
