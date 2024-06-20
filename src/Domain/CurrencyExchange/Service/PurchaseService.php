<?php

declare(strict_types= 1);

namespace Ifx\Task\Domain\CurrencyExchange\Service;

use Ifx\Task\Domain\CurrencyExchange\FeeCalculator;
use Ifx\Task\Domain\CurrencyExchange\NumericStringValidator;
use Ifx\Task\Domain\CurrencyExchange\ExchangeRateRepositoryInterface;
use Ifx\Task\Domain\CurrencyExchange\InvalidNumericStringException;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\Currency;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\Money;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\ExchangeRate;

class PurchaseService implements TransactionServiceInterface
{
    private FeeCalculator $calculator;
    private NumericStringValidator $validator;
    private ExchangeRateRepositoryInterface $exchangeRateRepository;

    public function __construct(
        FeeCalculator $calculator,
        NumericStringValidator $validator,
        ExchangeRateRepositoryInterface $exchangeRateRepository,
    ) {
        $this->calculator = $calculator;
        $this->validator = $validator;
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    public function executeExchange(Money $money, Currency $toCurrency): Money
    {
        $amountAfterExchange = $this->exchange($money, $toCurrency);
        $amountAfterFee = $this->calculator->calculateBuyFee($amountAfterExchange);

        return new Money($amountAfterFee, $toCurrency);
    }

    private function exchange(Money $money, Currency $toCurrency): string
    {
        $rate = $this->getExchangeRate($money->getCurrency(), $toCurrency);
        $this->validator->validate($money->getAmount());
        $this->validator->validate($rate->getRate());

        return bcmul($money->getAmount(), $rate->getRate(), FeeCalculator::BC_SCALE);
    }

    private function getExchangeRate(Currency $from, Currency $to): ExchangeRate
    {
        $rate = $this->exchangeRateRepository->findRate($from, $to);
        if ($rate === null)
            throw new InvalidNumericStringException("Invalid exchange rate.");
        
        return $rate;
    }
}
