NISS
==============

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helip/niss.svg?style=flat-square)](https://packagist.org/packages/helip/niss) [![Build Status](https://scrutinizer-ci.com/g/helinp/helip-niss/badges/build.png?b=master)](https://scrutinizer-ci.com/g/helinp/helip-niss/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/helinp/helip-niss/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/helinp/helip-niss/?branch=master) [![Total Downloads](https://img.shields.io/packagist/dt/helip/niss.svg?style=flat-square)](https://packagist.org/packages/helip/niss) ![PHP 7.4](https://img.shields.io/badge/PHP-7.4-green)

Helip/NISS is a PHP library that provides tools for generating and validating Belgian National Identification Numbers (NISS). 
The library includes a class called NISSGenerator for generating valid NISS numbers and providing relevant information, as well as three helper classes for validating NISS numbers. The library adheres to the specifications outlined in legal references related to the composition and validation of NISS numbers in Belgium.

Installation
------------

You can install the package via Composer:
```shell
composer require helip/niss
```

Getting Started
---------------
```php
<?php
// Include Composer autoloader
require_once 'vendor/autoload.php';

use Helip\NISS\NISS;

// Get a niss
$rawNiss = 'yymmdd-xxxx-cc';

// Validation
$validation = true;
try {
    NISSValidator::isValid($rawNiss);
} catch (Exception $e) {
    // Handle error
    echo $e->getMessage();
    $validation = false;
    // ...
}

if ($validation) {
    // Instantiates a Niss object
    $niss = new NISS($niss);
    
    // Get infos
    $birthdate = $niss->getBirthdate($niss);
    $gender = $niss->getGender()
    $type = $niss->getType();
    $formatted = $niss->getFormattedNiss();
    // ...
    
    // Do stuff
    // ...
}
```

Other example of use can be found at [/examples/index.php](/examples/index.php)

Notes
----

### NISS
National Identification Number (NISS): This is a unique number assigned to every Belgian citizen at birth or upon immigration. It consists of 11 digits, with the first six digits representing the person's date of birth, followed by a three-digit code indicating the municipality of birth, and ending with a two-digit control number. The NISS is used for a wide range of purposes, including social security, health care, and taxes.

### NISS BIS
The BIS number is a unique identification number assigned to individuals who are not registered in the national registry, but still maintain close and stable relationships with Belgium in various sectors such as social security, healthcare, and others.

### NISS TER
Temporary Identification Number (TER): This is a unique number generated on demand for the purpose of Covid-19 testing. It is not linked to any personal information or records and can only be used for the specific Covid-19 test it was generated for. The TER is not intended to replace the NISS or BIS and cannot be used for any other purposes outside of Covid-19 testing.

### Unoffical NISS type
The unofficial NISS number type (`const NISS::TYPE_UNOFFICIAL`) is a fictitious identifier created solely for the purpose of distinguishing the generated numbers from one another. It has no official value and is not recognized by any government agency. The use of this number does not in any way affect the validity or accuracy of the generated NISS numbers.


Limitations
-----------
### Accuracy of DOB calculation
Please note that in extremely rare cases, the calculation of the date of birth based on the Belgian National Identification Number (NISS) may not be accurate. This is due to a legal provision that states that if all possible sequence numbers have been used, the sixth digit representing the date of birth (in yymmdd format) is increased by one and the numbering in the sequence restarts from the beginning during a new registration. Therefore, it is important to verify the accuracy of the date of birth obtained from the NISS in case of doubts or inconsistencies.


References
----------

* [TI000 - Numéro d'identification](https://www.ibz.rrn.fgov.be/fileadmin/user_upload/fr/rn/instructions/liste-TI/TI000_Numero-identification.pdf) (French)
* [CBSS Manual - Belgian Social Security Card](https://www.ksz-bcss.fgov.be/sites/default/files/assets/services_et_support/cbss_manual_fr.pdf ) (French)
* [Arrêté royal relatif à la composition du numéro d'identification des personnes inscrites au Registre national des personnes physiques. ](https://www.ejustice.just.fgov.be/cgi_loi/change_lg.pl?language=fr&la=F&cn=1984040333&table_name=loi) (French|Dutch)

Disclaimer
--------

The NISS generator inclued in this package is provided for educational and testing purposes only. The generated NISS numbers are not official and should not be used for any official purpose. Any misuse of this tool to generate NISS numbers for fraudulent or illegal purposes is strictly prohibited and may lead to legal consequences. The author of this tool assumes no liability for any unauthorized or unlawful use of this tool.

Credits
-------
[Pierre Hélin](https://github.com/helinp)

License
-------
![gplv3-only](https://www.gnu.org/graphics/gplv3-88x31.png)
GPL-3.0. Please see [License File](https://www.gnu.org/licenses/gpl-3.0.txt) for more information.