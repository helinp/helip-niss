<?php

namespace Helip\NISS;

use DateTime;
use Helip\NISS\Helpers\NISSExtractorHelper;
use Helip\NISS\Helpers\NISSValidatorHelper;
use InvalidArgumentException;

/**
 * NISS
 *
 * This class represents a Belgian National Identification Number (NISS).
 *
 * @version 1.0.0
 * @license GPL-3.0
 * @author Pierre Hélin
 * @package NISS
 */
final class NISS
{
    public const TYPE_REGULAR = 'REGULAR';
    public const TYPE_DOB_UNKNOWN = 'UNKNOW DOB';
    public const TYPE_BIS = 'BIS';
    public const TYPE_TER = 'TER';
    public const TYPE_UNOFFICIAL = 'UNOFFICIAL'; // Special type for generated NISS

    public const GENDER_MALE = 'M';
    public const GENDER_FEMALE = 'F';
    public const GENDER_UNKNOWN = '';

    /**
     * The NISS number.
     *
     * @var string
     */
    private $niss;

    /**
     * The control number.
     *
     * @var string
     */
    private string $controlNumber;

    /**
     * The order number.
     *
     * @var string
     */
    private string $orderNumber;

    /**
     * The birthdate.
     *
     * @var DateTime | null
     */
    private ?DateTime $birthdate;

    /**
     * The gender
     *
     * @var string | null
     */
    private ?string $gender;

    /**
     * The type of NISS number
     *
     * @var string
     */
    private string $type;

    /**
     * Constructor.
     *
     * @param string $niss
     * @throws InvalidArgumentException
     *
     */
    public function __construct(string $niss)
    {
        // clean the number, remove non numeric characters
        $niss = NISSValidatorHelper::clean($niss);

        // Check validity of the number
        try {
            NISSValidatorHelper::isValid($niss);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('The NISS number is not valid: ' . $niss . '. ' . $e->getMessage());
        }

        $this->extractDataFromNISS($niss);
    }

    /**
     * Extracts data from the NISS number and sets the properties.
     *
     * @param string $niss
     * @return void
     */
    private function extractDataFromNISS(string $niss): void
    {
        // niss
        $this->niss = $niss;

        // extracts last 2 digits
        $this->controlNumber = NISSExtractorHelper::getControlNumber($this->niss);
        $this->orderNumber = NISSExtractorHelper::getOrderNumber($this->niss);
        $this->type = NISSExtractorHelper::calculateType($this->niss);
        $this->gender = NISSExtractorHelper::calculateGender($this->niss);
        $this->birthdate = NISSExtractorHelper::calculateBirthdate($this->niss, $this->type, $this->gender);
    }

    /**
     * Returns the NISS number.
     *
     * @return string
     */
    public function getNISS(): string
    {
        return $this->niss;
    }

    /**
     * Returns the NISS number.
     *
     * @return string
     */
    public function getFormattedNISS(): string
    {
        return NISSExtractorHelper::format($this->niss);
    }

    /**
     * Returns the control number.
     *
     * @return string
     */
    public function getControlNumber(): string
    {
        return $this->controlNumber;
    }

    /**
     * Returns the order number.
     *
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * Returns the birthdate or null if the birthdate is unknown.
     *
     * @return DateTime|null
     */
    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    /**
     * Returns the gender
     *
     * @return string M, F or null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * Returns the type of NISS number
     *
     * @return string default, bis or unofficial
     */
    public function getType(): string
    {
        return $this->type;
    }
}
