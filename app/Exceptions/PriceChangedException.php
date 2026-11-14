<?php

namespace App\Exceptions;

use Exception;

/** SRS 5.6: client's expected_total didn't match the server-recalculated total. */
class PriceChangedException extends Exception
{
    public function __construct(public readonly int $newTotal)
    {
        parent::__construct('The price has changed since you last viewed this cart.');
    }
}
