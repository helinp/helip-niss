<?php

namespace Helip\NISS\Helpers;

use InvalidArgumentException;

/**
 * NISSValidatorHelper
 *
 * This class provides static methods for validating Belgian National Identification
 * Numbers (NISS), including NISS BIS and TER.
 *
 * @version 1.0.0
 * @author Pierre Hélin
 * @license LGPL-3.0-only
 * @package NISS
 */
class NISSValidatorHelper
{
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

    /**
     * Check if a NISS number is valid.
     * Checkes the length and the control number.
     *
     * @param string $niss (11 digits only)
     *
     * @throws InvalidArgumentException
     * @return true or throws an exception
     */
    public static function isValid(string $niss): bool
    {
        // Check that the number is exactly 11 digits long
        if (strlen($niss) !== 11) {
            return false;
        }

        // Get the niss without the control number (first 9 digits)
        $numberWithoutControlNumber = substr($niss, 0, 9);

        // Get the control number (last 2 digits)
        $controlNumber = intval(substr($niss, 9, 2));

        // Check that the check digits are between 0 and 97
        $expectedCheckDigits = intval(97 - (intval($numberWithoutControlNumber) % 97));

        // Get the niss whith a 2 in front of the number (born after 1999)
        $numberWithTwo = '2' . $numberWithoutControlNumber;
        // Check that the check digits are between 0 and 97 (born after 1999)
        $expectedCheckDigitsWithTwo = intval(97 - (intval($numberWithTwo) % 97));

        if ($controlNumber != $expectedCheckDigits && $controlNumber != $expectedCheckDigitsWithTwo) {
            return false;
        }

        // The number is valid
        return true;
    }
}
