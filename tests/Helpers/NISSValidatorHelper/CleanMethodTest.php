<?php

use Helip\NISS\Helpers\NISSValidatorHelper;
use PHPUnit\Framework\TestCase;

class CleanMethodTest extends TestCase
{
    // CLEAN
    public function testCleanStripsNonDigits(): void
    {
        $this->assertSame('12345678901', NISSValidatorHelper::clean('12.34/56!78u 9.01'));
    }

    public function testCleanReturnsSameStringIfAlreadyClean(): void
    {
        $this->assertSame('12345678901', NISSValidatorHelper::clean('12345678901'));
    }
}
