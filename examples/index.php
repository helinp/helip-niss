<?php

use Helip\NISS\Helpers\NISSGeneratorHelper;
use Helip\NISS\NISS;

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

main();

function main(): void
{
    // Generates a random NISS numbers
    for ($i = 0; $i < 10; $i++) {
        // Generates random infos
        $birthDate = randomBirthdate();
        $order_number = random_int(1, 999);
        $gender = ($order_number % 2 === 0 ? NISS::GENDER_FEMALE : NISS::GENDER_MALE);
        $type = randomType();

        // Generates a NISS number
        $generatedNiss = NISSGeneratorHelper::generate($birthDate, $gender, null, $type);

        // Adds random chars to the NISS number to simulate random formatting
        $generatedNiss = insertRandomChars($generatedNiss, random_int(0, 10));

        // Instantiates a Niss object
        $niss = new NISS($generatedNiss);

        echo '================================================' . PHP_EOL;
        echo 'Gen. Birthdate:    ' . $birthDate->format('Y-m-d') . PHP_EOL;
        echo 'Gen. NISS:         ' . $generatedNiss . PHP_EOL;
        echo '------------------------------------------------' . PHP_EOL;
        echo 'Formatted NISS:    ' . $niss->getFormattedNiss() . PHP_EOL;
        echo 'Birthdate (Y-m-d): ' . ($niss->getBirthdate() ? $niss->getBirthdate()->format('Y-m-d') : 'Unknown') . PHP_EOL;
        echo 'Gender:            ' . $niss->getGender() . PHP_EOL;
        echo 'Type of NISS:      ' . $niss->getType() . PHP_EOL;
        echo 'Order number:      ' . $niss->getOrderNumber() . PHP_EOL;
        echo 'Control number:    ' . $niss->getControlNumber() . PHP_EOL;
        echo '================================================' . PHP_EOL . PHP_EOL;
    }
}

function randomBirthdate($age_min = 8, $age_max = 99): DateTime
{
    $current_year = date('Y');

    $max = strtotime($current_year - $age_max . '-01-01');
    $min = strtotime($current_year - $age_min . '-12-31');

    $val = rand($min, $max);

    return new DateTime(date('Y-m-d', $val));
}

function randomType(): string
{
    $type = [NISS::TYPE_REGULAR, NISS::TYPE_BIS, NISS::TYPE_UNOFFICIAL, NISS::TYPE_TER, NISS::TYPE_DOB_UNKNOWN];
    return $type[array_rand($type)];
}

// Adds random chars to a string
function insertRandomChars(string $str, int $numChars): string
{
    $chars = str_split('.- _:,;/@');
    $len = strlen($str);

    for ($i = 0; $i < $numChars; $i++) {
        $pos = mt_rand(0, $len);
        $char = $chars[mt_rand(0, count($chars) - 1)];
        $str = substr_replace($str, $char, $pos, 0);
    }

    return $str;
}
