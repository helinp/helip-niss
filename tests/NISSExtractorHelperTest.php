<?php

namespace Tests\Helpers;

use DateTime;
use Helip\NISS\Helpers\NISSExtractorHelper;
use Helip\NISS\Helpers\NISSValidatorHelper;
use Helip\NISS\NISS;
use PHPUnit\Framework\TestCase;

class NISSExtractorHelperTest extends TestCase
{
    // Clean

    public function testCleanRemovesNonNumericCharacters(): void
    {
        $niss = '12.34/56!78u 9.01';
        $this->assertEquals('12345678901', NISSValidatorHelper::clean($niss));
    }

    public function testCleanReturnsIdenticalIfAlreadyClean(): void
    {
        $niss = '12345678901';
        $this->assertEquals('12345678901', NISSValidatorHelper::clean($niss));
    }

    // Birthdate

    public function testBirthdateIsNullWhenTypeIsUnknown(): void
    {
        $this->assertNull(NISSExtractorHelper::calculateBirthdate(
            '00000112341',
            NISS::TYPE_DOB_UNKNOWN,
            NISS::GENDER_MALE
        ));
    }

    public function testBirthdateIsExtractedForRegularNiss(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('05020940753', NISS::TYPE_REGULAR, NISS::GENDER_MALE);
        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals('2005-02-09', $date->format('Y-m-d'));
    }

    public function testBirthdateIsExtractedForBisMale(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('94521328514', NISS::TYPE_BIS, NISS::GENDER_MALE);
        $this->assertEquals('1994-12-13', $date->format('Y-m-d'));
    }

    public function testBirthdateIsExtractedForBisFemale(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('11522801461', NISS::TYPE_BIS, NISS::GENDER_FEMALE);
        $this->assertEquals('2011-12-28', $date->format('Y-m-d'));
    }

    public function testBirthdateIsExtractedForBisUnknown(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('11322801418', NISS::TYPE_BIS, NISS::GENDER_UNKNOWN);
        $this->assertEquals('2011-12-28', $date->format('Y-m-d'));
    }

    public function testBirthdateIsExtractedForUnofficial(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('07832531877', NISS::TYPE_UNOFFICIAL, NISS::GENDER_FEMALE);
        $this->assertEquals('2007-03-25', $date->format('Y-m-d'));
    }

    // Gender

    public function testGenderIsMale(): void
    {
        $this->assertEquals(NISS::GENDER_MALE, NISSExtractorHelper::calculateGender('18041986666'));
    }

    public function testGenderIsFemale(): void
    {
        $this->assertEquals(NISS::GENDER_FEMALE, NISSExtractorHelper::calculateGender('05482670057'));
    }

    // Type

    public function testTypeIsRegular(): void
    {
        $this->assertEquals(NISS::TYPE_REGULAR, NISSExtractorHelper::calculateType('91072413835'));
    }

    public function testTypeIsBis(): void
    {
        $this->assertEquals(NISS::TYPE_BIS, NISSExtractorHelper::calculateType('04511888515'));
    }

    public function testTypeIsUnofficial(): void
    {
        $this->assertEquals(NISS::TYPE_UNOFFICIAL, NISSExtractorHelper::calculateType('01840693112'));
    }
}
