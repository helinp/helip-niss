<?php

use Helip\NISS\Helpers\NISSValidatorHelper;
use PHPUnit\Framework\TestCase;

class IsValidMethodTest extends TestCase
{
    public function testValidNiss()
    {
        $this->assertTrue(NISSValidatorHelper::isValid('13820404235'));
    }

    public function testValidBisNiss()
    {
        $this->assertTrue(NISSValidatorHelper::isValid('14482162979'));
    }

    public function testValidUnofficialNiss()
    {
        $this->assertTrue(NISSValidatorHelper::isValid('98850638452'));
    }

    public function testInvalidNissControlNumber()
    {
        $this->assertFalse(NISSValidatorHelper::isValid('97041812346'));
    }

    public function testInvalidBisControlNumber()
    {
        $this->assertFalse(NISSValidatorHelper::isValid('97461564896'));
    }

    public function testInvalidUnofficialControlNumber()
    {
        $this->assertFalse(NISSValidatorHelper::isValid('98850638451'));
    }

    public function testValidUnknownBirthDateNiss()
    {
        $this->assertTrue(NISSValidatorHelper::isValid('00000112341'));
    }
}
