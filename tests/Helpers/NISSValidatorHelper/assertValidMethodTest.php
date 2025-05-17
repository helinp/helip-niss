<?php

use Helip\NISS\Helpers\NISSValidatorHelper;
use Helip\NISS\Exception\InvalidFormatException;
use Helip\NISS\Exception\InvalidControlNumberException;
use PHPUnit\Framework\TestCase;

class AssertValidMethodTest extends TestCase
{
    public function testThrowsExceptionWhenNissIsTooShort(): void
    {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('NISS must be 11 digits long');
        NISSValidatorHelper::assertValid('123456789');
    }

    public function testThrowsExceptionWhenNissIsForbidden(): void
    {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('NISS cannot be 00000000000');
        NISSValidatorHelper::assertValid('00000000000');
    }

    public function testThrowsExceptionWhenNissHasInvalidControlNumber(): void
    {
        $this->expectException(InvalidControlNumberException::class);
        NISSValidatorHelper::assertValid('13820404234'); // faux dernier chiffre
    }

    public function testDoesNotThrowOnValidNiss(): void
    {
        $this->expectNotToPerformAssertions();
        NISSValidatorHelper::assertValid('13820404235'); // valide
    }
}
