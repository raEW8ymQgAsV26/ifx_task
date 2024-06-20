<?php

declare(strict_types=1);

namespace Ifx\Task\Tests\Domain;

use Ifx\Task\Domain\CurrencyExchange\FeeCalculator;
use Ifx\Task\Domain\CurrencyExchange\NumericStringValidator;
use Ifx\Task\Domain\CurrencyExchange\ExchangeRateRepositoryInterface;
use Ifx\Task\Domain\CurrencyExchange\Service\SellService;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\Currency;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\Money;
use Ifx\Task\Domain\CurrencyExchange\ValueObject\ExchangeRate;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SellService::class)]
final class SellServiceTest extends TestCase
{
    private $exchangeRepositoryMock;
    private SellService $service;

    public function setUp(): void
    {
        $exchangeRates = [
            new ExchangeRate(new Currency("EUR"), new Currency("GBP"), '1.5678'),
            new ExchangeRate(new Currency("GBP"), new Currency("EUR"), '1.5432'),
        ];

        $validatorMock = $this->createMock(NumericStringValidator::class);
        $repositoryMock = $this->createMock(ExchangeRateRepositoryInterface::class);
        $repositoryMock->method('findRate')
            ->willReturnCallback(function ($from, $to) use ($exchangeRates) {
                foreach ($exchangeRates as $rate) {
                    if ($rate->getFrom()->getCode() === $from->getCode() && $rate->getTo()->getCode() === $to->getCode()) {
                        return $rate;
                    }
                }
                return null;
        });

        $this->service = new SellService(new FeeCalculator('0.01', $validatorMock), $validatorMock, $repositoryMock);
    }
    
    #[DataProvider("clientSellingCurrencyProvider")]
    public function testClientSellingCurrency(string $amount, string $fromCurrency, string $toCurrency, string $expectedAmount): void
    {
        $money = new Money($amount, new Currency($fromCurrency));
        $result = $this->service->executeExchange($money, new Currency($toCurrency));

        $this->assertEquals($result->getAmount(), $expectedAmount);
        $this->assertEquals($result->getCurrency()->getCode(), $toCurrency);
    }

    public static function clientSellingCurrencyProvider(): array
    {
        return [
            // amount, from, to, expectedAmount
            ['100.00', 'EUR', 'GBP', '155.21220000'],
            ['100.00', 'GBP', 'EUR', '152.77680000'],
        ];
    }
}
