<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange;

class NumericStringValidator
{
    public function validate(string $amount): bool
    {
        $pattern = '/^[+-]?[0]*[1-9]*[.]?[0-9]+$/';
        if (!preg_match($pattern, $amount) || bccomp($amount, '0', 8) <= 0)
            throw new InvalidNumericStringException("Invalid amount. Must be a numeric string and greater than zero.");

        return true;
    }
}
