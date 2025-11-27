<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

/**
 * Exception levée lorsqu'un montant invalide est utilisé.
 */
class InvalidAmountException extends Exception
{
}

