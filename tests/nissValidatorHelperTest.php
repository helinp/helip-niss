<?php

use Helip\NISS\Helpers\NISSValidatorHelper;
use PHPUnit\Framework\TestCase;

class NISSValidatorHelperTest extends TestCase
{
    public function testIsValid()
    {
        // Test with a valid NISS number
        $this->assertTrue(NISSValidatorHelper::isValid('13820404235'));

        // Test with a BIS valid NISS number
        $this->assertTrue(NISSValidatorHelper::isValid('14482162979'));

        // Test with a Unofficial valid NISS number
        $this->assertTrue(NISSValidatorHelper::isValid('98850638452'));

        // Test with an invalid NISS number (wrong length)
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('NISS must be 11 digits long');
        NISSValidatorHelper::isValid('9704181234');

        // Test with an invalid NISS number (wrong control number)
        $this->expectException(NISSValidatorHelper::isValid('97041812346'));

        // Test with an invalid BIS number (wrong control number)
        $this->expectException(NISSValidatorHelper::isValid('97461564896'));

        // Test with an invalid Unofficial NISS number (wrong control number)
        $this->expectException(NISSValidatorHelper::isValid('98850638451'));
  
        // Test with unknown date of birth
        $this->assertTrue(NISSValidatorHelper::isValid('00000112341'));
    }
}
