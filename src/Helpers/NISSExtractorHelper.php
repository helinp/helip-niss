<?php

namespace Helip\NISS\Helpers;

use DateTime;
use Helip\NISS\NISS;
use InvalidArgumentException;

/**
 * NISSExtractorHelper
 *
 * This class contains static methods to retrieve information from and clean
 * National Identification Numbers (NISS) and National Identification Numbers BIS and TER
 *
 * @version 1.0.0
 * @license LGPL-3.0-only
 * @author Pierre Hélin
 * @package NISS
 */
class NISSExtractorHelper
{
    /**
     * Calculates the birthdate.
     *
     * @param string $niss
     * @return DateTime|null
     */
    public static function calculateBirthdate(string $niss, string $type, string $gender): DateTime|null
    {
        // Dob cannot be calculated if type is unknown
        if ($type == NISS::TYPE_DOB_UNKNOWN) {
            return null;
        }

        // extract birthdate
        $birthdateString = substr($niss, 0, 6);

        // extract month
        $month = intval(substr($birthdateString, 2, 2));

        // calculate month based on type
        if ($type == NISS::TYPE_BIS && $gender === NISS::GENDER_UNKNOWN) {
            $month = $month - 20;
        } elseif ($type == NISS::TYPE_BIS) {
            $month = $month - 40;
        } elseif ($type == NISS::TYPE_TER) {
            $month = $month - 60;
        } elseif ($type == NISS::TYPE_UNOFFICIAL) {
            $month = $month - 80;
        }

        // ensure month is 2 digits
        if ($month < 10) {
            $month = '0' . $month;
        }

        // check if year is greater than 1999
        $century = self::getCentury($niss);

        // replace month
        $newBirthdateString = $century . substr($birthdateString, 0, 2) . $month . substr($birthdateString, 4, 2);

        // convert to date
        return DateTime::createFromFormat('Ymd', $newBirthdateString);
    }

    /**
     * Calculates gender based on the control number.
     *
     * @param string $niss
     *
     * @return string
     */
    public static function calculateGender(string $niss): string
    {
        // gender is based on control number odd (male) or even (female)
        $controlNumber = (int) self::getControlNumber($niss);
        return ($controlNumber % 2 == 1 ? NISS::GENDER_FEMALE : NISS::GENDER_MALE);
    }

    /**
     * Calculate type of NISS number
     *
     * @param string $niss
     *
     * @return string
     */
    public static function calculateType($niss): string
    {
        // extract month
        $month = substr($niss, 2, 2);

        // check if month is greater than 0
        if ($month === '00') {
            return NISS::TYPE_DOB_UNKNOWN;
        } elseif ($month > 0 && $month < 13) {
            return NISS::TYPE_REGULAR;
            // BIS type whith unknow gender are augmented by 20
        } elseif ($month > 0 + 20 && $month < 13 + 20) {
            return NISS::TYPE_BIS;
            // BIS type whith know gender are augmented by 40
        } elseif ($month > 0 + 40 && $month < 13 + 40) {
            return NISS::TYPE_BIS;
            // TER type are augmented by 60
        } elseif ($month > 0 + 60 && $month < 13 + 60) {
            return NISS::TYPE_TER;
            // unofficial type are augmented by 80
        } elseif ($month > 0 + 80 && $month < 13 + 80) {
            return NISS::TYPE_UNOFFICIAL;
        } else {
            return NISS::TYPE_UNKNOWN;
        }
    }

    /**
     * Get the century based on the control number.
     */
    public static function getCentury(string $niss): int
    {
        // extract control number
        $controlNumber = (int) self::getControlNumber($niss);
        $reducedNISS = substr($niss, 0, 9);

        $calculatedControlNumber = self::calculateControlNumber($reducedNISS, false);
        if ($calculatedControlNumber === $controlNumber) {
            // control number is valid for 1900-1999
            return 19;
        }
        $calculatedControlNumber = self::calculateControlNumber($reducedNISS, true);
        if ($calculatedControlNumber === $controlNumber) {
            // control number is valid for 2000-2099
            return 20;
        }

        throw new InvalidArgumentException('Invalid NISS number');
    }

    /**
     * Get the control number.
     *
     * @param string $niss
     *
     * @return string
     */
    public static function getControlNumber(string $niss): string
    {
        return substr($niss, -2);
    }

    /**
     * Get the order number.
     *
     * @param string $niss
     *
     * @return string
     */
    public static function getOrderNumber(string $niss): string
    {
        return substr($niss, -5, 3);
    }

    /**
     * Format a NISS number.
     * YY.MM.DD-XXX.CC
     *
     * @param string $niss
     *
     * @return string
     */
    public static function format(string $niss): string
    {
        return substr($niss, 0, 2) . '.'
            . substr($niss, 2, 2) . '.'
            . substr($niss, 4, 2) . '-'
            . substr($niss, 6, 3) . '.'
            . substr($niss, 9, 2);
    }

    /**
     * Calculate the control number.
     *
     * @param string $reducedNISS
     * @param bool $bornAfter1999
     *
     * @return int
     */
    public static function calculateControlNumber(string $reducedNISS, bool $bornAfter1999): int
    {
        // add a leading 2 to the string if the birth date is after 1999
        // ref.: Arrêté royal du 25 novembre 1997 paru au Moniteur belge du 16 décembre 1997
        if ($bornAfter1999) {
            $reducedNISS = 2 . $reducedNISS;
        }

        return intval(97 - (intval($reducedNISS) % 97));
    }
}
