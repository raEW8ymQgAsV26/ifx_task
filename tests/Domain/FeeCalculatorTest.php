<?php

declare(strict_types=1);

namespace Ifx\Task\Tests\Domain;

use Ifx\Task\Domain\CurrencyExchange\FeeCalculator;
use Ifx\Task\Domain\CurrencyExchange\NumericStringValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FeeCalculator::class)]
final class FeeCalculatorTest extends TestCase
{
    private FeeCalculator $calculator;

    public function setUp(): void
    {
        $validatorMock = $this->createMock(NumericStringValidator::class);
        $this->calculator = new FeeCalculator('0.01', $validatorMock);
    }

    #[DataProvider("saleProvider")]
    public function testCalculateFeeForClientSelling(string $amount, string $expectedPrice): void
    {
        $priceWithfee = $this->calculator->calculateSellFee($amount);

        $this->assertEquals($expectedPrice, $priceWithfee);
    }

    #[DataProvider("buyProvider")]
    public function testCalculateFeeForClientBuying(string $amount, string $expectedPrice): void
    {
        $priceWithFee = $this->calculator->calculateBuyFee($amount);

        $this->assertEquals($expectedPrice, $priceWithFee);
    }

    public static function saleProvider(): array
    {
        return [
            ['156.78', '155.21220000'],
            ['154.32', '152.77680000'],
        ];
    }

    public static function buyProvider(): array
    {
        return [
            ['154.32', '155.86320000'],
            ['156.78', '158.34780000'],
        ];
    }
}