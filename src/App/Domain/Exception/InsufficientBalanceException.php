<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

/**
 * Exception levée lorsque le solde est insuffisant pour une opération.
 */
class InsufficientBalanceException extends Exception
{
}

