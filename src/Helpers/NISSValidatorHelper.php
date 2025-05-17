<?php

namespace Helip\NISS\Helpers;

use Helip\NISS\Exception\InvalidControlNumberException;
use Helip\NISS\Exception\InvalidFormatException;
use Helip\NISS\Exception\InvalidNissExceptionInterface;

/**
 * NISSValidatorHelper
 *
 * This class provides static methods for validating Belgian National Identification
 * Numbers (NISS), including NISS BIS and TER.
 */
class NISSValidatorHelper
{
    /**
     * Check if a NISS number is valid.
     * Check the length and the control number.
     *
     * @param string $niss (11 digits only)
     *
     * @return bool
     */
    public static function isValid(string $niss): bool
    {
        try {

            self::assertValid($niss);
        } catch (InvalidNissExceptionInterface $e) {
            return false;
        }
        return true;
    }

    /**
     * Assert that a NISS number is valid, throwing an exception if it is not.
     * 
     * @param string $niss (11 digits only)
     * 
     * @throws InvalidArgumentException
     */
    public static function assertValid(string $niss): void
    {
        $niss = self::clean($niss);

        self::checkFormat($niss);
        self::checkControlNumberRange($niss);
        self::checkControlNumber($niss);
    }

    /**
     * Clean a NISS number removing all non numeric characters.
     *
     * @param string $niss
     *
     * @return string
     */
    public static function clean(string $niss): string
    {
        // remove all non numeric characters
        return preg_replace('/[^0-9]/', '', $niss);
    }

    private static function checkFormat(string $niss): void
    {
        if (strlen($niss) !== 11) {
            throw new InvalidFormatException('NISS must be 11 digits long');
        }

        if ($niss === '00000000000') {
            throw new InvalidFormatException('NISS cannot be 00000000000');
        }
    }

    private static function checkControlNumber(string $niss): void
    {
        // Get the niss without the control number (first 9 digits)
        $numberWithoutControlNumber = substr($niss, 0, 9);

        // Get the control number (last 2 digits)
        $controlNumber = (int) substr($niss, 9, 2);

        // Check that the check digits are between 0 and 97
        $expectedCheckDigits = (int) (97 - (int) $numberWithoutControlNumber % 97);

        // Get the niss whith a 2 in front of the number (born after 1999)
        $numberWithTwo = '2' . $numberWithoutControlNumber;

        // Check that the check digits are between 0 and 97 (born after 1999)
        $expectedCheckDigitsWithTwo = (int) (97 - (int) $numberWithTwo % 97);

        if ($controlNumber != $expectedCheckDigits && $controlNumber != $expectedCheckDigitsWithTwo) {
            throw new InvalidControlNumberException();
        }
    }

    private static function checkControlNumberRange(string $niss): void
    {
        // Check that the control number is between 0 and 97
        $controlNumber = (int) substr($niss, 9, 2);
        if ($controlNumber < 0 || $controlNumber > 97) {
            throw new InvalidControlNumberException();
        }
    }
}
