<?php

use Helip\NISS\Helpers\NISSValidatorHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NISSValidatorHelperTest extends TestCase
{
    // Cas valides
    public function testValidRegularNISS(): void
    {
        $this->assertTrue(NISSValidatorHelper::isValid('13820404235'));
    }

    public function testValidBISNISS(): void
    {
        $this->assertTrue(NISSValidatorHelper::isValid('14482162979'));
    }

    public function testValidUnofficialNISS(): void
    {
        $this->assertTrue(NISSValidatorHelper::isValid('98850638452'));
    }

    public function testValidUnknownBirthDate(): void
    {
        $this->assertTrue(NISSValidatorHelper::isValid('00000112341'));
    }

    // Cas invalides

    public function testInvalidLengthThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('NISS must be 11 digits long');
        NISSValidatorHelper::isValid('9704181234');
    }

    public function testInvalidControlNumberRegularThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        NISSValidatorHelper::isValid('97041812346');
    }

    public function testInvalidControlNumberBISThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        NISSValidatorHelper::isValid('97461564896');
    }

    public function testInvalidControlNumberUnofficialThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        NISSValidatorHelper::isValid('98850638451');
    }
}
