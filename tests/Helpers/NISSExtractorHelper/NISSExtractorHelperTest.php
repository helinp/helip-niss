<?php

namespace Tests\Helpers\NISSExtractorHelper;

use DateTime;
use Helip\NISS\Helpers\NISSExtractorHelper;
use Helip\NISS\Helpers\NISSValidatorHelper;
use Helip\NISS\NISS;
use PHPUnit\Framework\TestCase;

class NISSExtractorHelperTest extends TestCase
{
    // BIRTHDATE
    public function testBirthdateIsNullWhenDateOfBirthIsUnknown(): void
    {
        $this->assertNull(NISSExtractorHelper::calculateBirthdate('00000112341', NISS::TYPE_DOB_UNKNOWN, NISS::GENDER_MALE));
    }

    public function testBirthdateIsParsedForRegularNiss(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('05020940753', NISS::TYPE_REGULAR, NISS::GENDER_MALE);
        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals('2005-02-09', $date->format('Y-m-d'));
    }

    public function testBirthdateIsParsedForBisMale(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('94521328514', NISS::TYPE_BIS, NISS::GENDER_MALE);
        $this->assertEquals('1994-12-13', $date->format('Y-m-d'));
    }

    public function testBirthdateIsParsedForBisFemale(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('11522801461', NISS::TYPE_BIS, NISS::GENDER_FEMALE);
        $this->assertEquals('2011-12-28', $date->format('Y-m-d'));
    }

    public function testBirthdateIsParsedForBisUnknown(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('11322801418', NISS::TYPE_BIS, NISS::GENDER_UNKNOWN);
        $this->assertEquals('2011-12-28', $date->format('Y-m-d'));
    }

    public function testBirthdateIsParsedForUnofficial(): void
    {
        $date = NISSExtractorHelper::calculateBirthdate('07832531877', NISS::TYPE_UNOFFICIAL, NISS::GENDER_FEMALE);
        $this->assertEquals('2007-03-25', $date->format('Y-m-d'));
    }

    // GENDER
    public function testCalculateGenderReturnsMale(): void
    {
        $this->assertEquals(NISS::GENDER_MALE, NISSExtractorHelper::calculateGender('18041986666'));
    }

    public function testCalculateGenderReturnsFemale(): void
    {
        $this->assertEquals(NISS::GENDER_FEMALE, NISSExtractorHelper::calculateGender('05482670057'));
    }

    // TYPE
    public function testCalculateTypeReturnsRegular(): void
    {
        $this->assertEquals(NISS::TYPE_REGULAR, NISSExtractorHelper::calculateType('91072413835'));
    }

    public function testCalculateTypeReturnsBis(): void
    {
        $this->assertEquals(NISS::TYPE_BIS, NISSExtractorHelper::calculateType('04511888515'));
    }

    public function testCalculateTypeReturnsUnofficial(): void
    {
        $this->assertEquals(NISS::TYPE_UNOFFICIAL, NISSExtractorHelper::calculateType('01840693112'));
    }
}
