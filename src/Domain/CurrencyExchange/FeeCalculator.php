<?php 

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange;

class FeeCalculator
{
    const BC_SCALE = 8;

    private NumericStringValidator $validator;
    private string $exchangeFee;

    public function __construct(string $exchangeFee, NumericStringValidator $validator)
    {
        $this->validator = $validator;
        $this->validator->validate($exchangeFee);
        $this->exchangeFee = $exchangeFee;
    }

    public function calculateSellFee(string $amount): string
    {
        $this->validator->validate($amount);
        return bcsub($amount, $this->getFee($amount), self::BC_SCALE);
    }

    public function calculateBuyFee(string $amount): string
    {
        $this->validator->validate($amount);
        return bcadd($amount, $this->getFee($amount), self::BC_SCALE);
    }

    private function getFee(string $amount): string
    {
        return bcmul($amount, $this->exchangeFee, self::BC_SCALE);
    }
}
