<?php 

namespace Helip\NISS\Exception;

use InvalidArgumentException;

abstract class InvalidNissException extends InvalidArgumentException implements InvalidNissExceptionInterface {}
