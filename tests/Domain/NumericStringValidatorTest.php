<?php

declare(strict_types=1);

namespace Ifx\Task\Tests\Domain;

use Ifx\Task\Domain\CurrencyExchange\NumericStringValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NumericStringValidator::class)]
final class NumericStringValidatorTest extends TestCase
{
    private NumericStringValidator $validator;
  
    public function setUp(): void
    {
        $this->validator = new NumericStringValidator();
    }

    #[DataProvider("invalidInputProvider")]
    public function testValidateThrowsExceptionForInvalidData($input): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->validator->validate($input);
    }

    #[DataProvider("validInputProvider")]
    public function testValidateDoesNotThrowExceptionForValidData($input): void
    {
        $this->assertTrue(
            $this->validator->validate($input)
        );
    }

    public static function invalidInputProvider(): array
    {
        return [
            [''],
            ['0'],
            ['1337e0'],
            ['asdfasd'],
            ['0,01'],
        ];
    }

    public static function validInputProvider(): array
    {
        return [
            ['1'],
            ['0.000001'],
            ['1.1337'],
            ['123123123'],
        ];
    }
}