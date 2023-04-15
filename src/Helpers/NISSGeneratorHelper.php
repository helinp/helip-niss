<?php

namespace Helip\NISS\Helpers;

use DateTime;
use Helip\NISS\NISS;
use InvalidArgumentException;

/**
 * NISSGeneratorHelper
 *
 * This class contains static methods to generate National Identification Numbers (NISS)
 * and National Identification Numbers BIS and TER
 *
 * @version 1.0.0
 * @license LGPL-3.0-only
 * @author Pierre Hélin
 * @package NISS
 */
class NISSGeneratorHelper
{
    /**
     * Generate a belgian NISS number
     *
     * @param DateTime $birthDate
     * @param ?int $orderNumber
     * @param string $gender M, F or null
     * @param string $type default or bis
     *
     * @return string
     */
    public static function generate(DateTime $birthDate, string $gender, ?int $orderNumber = null, string $type)
    {
        // Checks orderNumber
        if ($orderNumber !== null && ($orderNumber < 1 || $orderNumber > 999)) {
            throw new InvalidArgumentException('The order number must be null or between 1 and 999.');
        }

        // Checks gender
        if ($gender !== NISS::GENDER_FEMALE && $gender !== NISS::GENDER_MALE && $gender !== NISS::GENDER_UNKNOWN) {
            throw new InvalidArgumentException('The gender must be null, F or M. Given: ' . $gender);
        }

        // TODO: Function + match bis and gender unknown
        // check if order number matches the gender
        if ($orderNumber == !null && $gender !== NISS::GENDER_UNKNOWN) {
            $isEven = $orderNumber % 2 == 0;
            if (($isEven && $gender === NISS::GENDER_MALE) || (!$isEven && $gender === NISS::GENDER_FEMALE)) {
                throw new InvalidArgumentException('The gender does not match the order number.');
            }
        }

        // generate the dob string matching the type
        $birthString = self::modifyDateOfBirth($birthDate, $type, $gender);

        // generate the order number, matching the gender
        $orderString = self::generateStringOrderNumber($orderNumber, $gender);

        // generate the control number
        $controlNumber = self::generateStringControlNumber($birthDate, $birthString, $orderString);

        return $birthString . $orderString . $controlNumber;
    }


    /**
     * return date of birth of a NISS number matching the type.
     *
     * @param DateTime $birthDate
     * @param string $type (default, bis or ter)
     * @param string $gender (M, F or null)
     *
     * @return string
     */
    public static function modifyDateOfBirth(DateTime $birthDate, string $type, string $gender): string
    {
        if ($type === NISS::TYPE_DOB_UNKNOWN) {
            return '000001';
        } elseif ($type === NISS::TYPE_REGULAR) {
            $month = $birthDate->format('m');
        } elseif ($type === NISS::TYPE_UNOFFICIAL) {
            // add 80 to the mounth if the NISS is unofficial
            $month = ((int) $birthDate->format('m')) + 80;
        } elseif ($type === NISS::TYPE_BIS && $gender !== NISS::GENDER_UNKNOWN) {
            // add 40 if gender is known
            $month = ((int) $birthDate->format('m')) + 40;
        } elseif ($type === NISS::TYPE_BIS && $gender == NISS::GENDER_UNKNOWN) {
            // add 20 if gender is unknown
            $month = ((int) $birthDate->format('m')) + 20;
        } elseif ($type === NISS::TYPE_TER) {
            // add 60 if NISS is ter
            $month = ((int) $birthDate->format('m')) + 60;
        } else {
            throw new InvalidArgumentException('The type must be default, bis or ter. Given: ' . $type);
        }

        return $birthDate->format('y') . str_pad($month, 2, '0', STR_PAD_LEFT) . $birthDate->format('d');
    }


    /**
     * Generate a control number.
     *
     * @param dateTime $birthString
     * @param string $orderString
     * @param ?string $type (default, bis or ter)
     *
     * @return string
     */
    private static function generateStringControlNumber(
        DateTime $birthDate,
        string $birthString,
        string $orderString
    ): string {
        // concatenate the birth date and the order number
        $unionString = $birthString . $orderString;
        $isAfter1999 = intval($birthDate->format('Y')) > 1999;

        $controlNumber = NISSExtractorHelper::calculateControlNumber($unionString, $isAfter1999);

        return str_pad($controlNumber, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a random order number, based on gender
     *
     * @param ?int $orderNumber
     * @param ?string $gender M, F or null
     *
     * @return string
     */
    private static function generateStringOrderNumber(?string $orderNumber = null, ?string $gender = null): string
    {
        // sanity check
        if ($orderNumber !== null && ($orderNumber < 1 || $orderNumber > 999)) {
            throw new InvalidArgumentException('The order number must be null or between 1 and 999.');
        }

        if ($orderNumber) {
            $order = $orderNumber;
        } elseif ($gender === NISS::GENDER_UNKNOWN) {
            $order = rand(1, 999);
        } else {
            $order = 1 + (rand(0, 499) * 2);
            if ($gender === NISS::GENDER_MALE) {
                $order--;
            }
        }

        return str_pad($order, 3, '0', STR_PAD_LEFT);
    }
}
