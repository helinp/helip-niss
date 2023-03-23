<?php

namespace Tests\Helpers;

use DateTime;
use Helip\NISS\Helpers\NISSExtractorHelper;
use Helip\NISS\Helpers\NISSValidatorHelper;
use Helip\NISS\NISS;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * NISSExtractorHelperTest
 * 
 * @version 1.0.0
 * @license GPL-3.0
 * 
 * @author Pierre Hélin
 * @package NISS
 * 
 * Note: All NISS numbers used in this test suite are randomly generated and are not intended to represent real persons.
 */
class NISSExtractorHelperTest extends TestCase
{
    public function testClean(): void
    {
        $niss = '12.34/56!78u 9.01';
        $cleanedNiss = NISSValidatorHelper::clean($niss);
        $this->assertEquals('12345678901', $cleanedNiss);

        $niss = '12345678901';
        $cleanedNiss = NISSValidatorHelper::clean($niss);
        $this->assertEquals('12345678901', $cleanedNiss);
    }

    public function testCalculateBirthdate(): void
    {
        $nissDobUnknown = '00000112341';
        $birthdateUnknown = NISSExtractorHelper::calculateBirthdate($nissDobUnknown, NISS::TYPE_DOB_UNKNOWN, NISS::GENDER_MALE);
        $this->assertNull($birthdateUnknown);

        $niss = '05020940753';
        $birthdate = NISSExtractorHelper::calculateBirthdate($niss, NISS::TYPE_REGULAR, NISS::GENDER_MALE);
        $this->assertInstanceOf(DateTime::class, $birthdate);
        $this->assertEquals('2005-02-09', $birthdate->format('Y-m-d'));

        $nissBisMale = '94521328514';
        $birthdateBisMale = NISSExtractorHelper::calculateBirthdate($nissBisMale, NISS::TYPE_BIS, NISS::GENDER_MALE);
        $this->assertInstanceOf(DateTime::class, $birthdateBisMale);
        $this->assertEquals('1994-12-13', $birthdateBisMale->format('Y-m-d'));

        $nissBisFemale = '11522801461';
        $birthdateBisFemale = NISSExtractorHelper::calculateBirthdate($nissBisFemale, NISS::TYPE_BIS, NISS::GENDER_FEMALE);
        $this->assertInstanceOf(DateTime::class, $birthdateBisFemale);
        $this->assertEquals('2011-12-28', $birthdateBisFemale->format('Y-m-d'));

        $nissBisUnknown = '11322801418';
        $birthdateBisFemale = NISSExtractorHelper::calculateBirthdate($nissBisUnknown, NISS::TYPE_BIS, NISS::GENDER_UNKNOWN);
        $this->assertInstanceOf(DateTime::class, $birthdateBisFemale);
        $this->assertEquals('2011-12-28', $birthdateBisFemale->format('Y-m-d'));

        $nissUnofficial = '07832531877';
        $birthdateUnofficial = NISSExtractorHelper::calculateBirthdate($nissUnofficial, NISS::TYPE_UNOFFICIAL, NISS::GENDER_FEMALE);
        $this->assertInstanceOf(DateTime::class, $birthdateUnofficial);
        $this->assertEquals('2007-03-25', $birthdateUnofficial->format('Y-m-d'));
    }

    public function testCalculateGender(): void
    {
        $nissMale = '18041986666';
        $genderMale = NISSExtractorHelper::calculateGender($nissMale);
        $this->assertEquals(NISS::GENDER_MALE, $genderMale);

        $nissFemale = '05482670057';
        $genderFemale = NISSExtractorHelper::calculateGender($nissFemale);
        $this->assertEquals(NISS::GENDER_FEMALE, $genderFemale);
    }

    public function testCalculateType(): void
    {
        $nissDefault = '91072413835';
        $typeDefault = NISSExtractorHelper::calculateType($nissDefault);
        $this->assertEquals(NISS::TYPE_REGULAR, $typeDefault);

        $nissBis = '04511888515';
        $typeBis = NISSExtractorHelper::calculateType($nissBis);
        $this->assertEquals(NISS::TYPE_BIS, $typeBis);

        $nissUnofficial = '01840693112';
        $typeUnofficial = NISSExtractorHelper::calculateType($nissUnofficial);
        $this->assertEquals(NISS::TYPE_UNOFFICIAL, $typeUnofficial);
    }

}