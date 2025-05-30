<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;

/**
 * A response that indicates success but carries no data.
 */
final class EmptyResult extends Result
{
    protected function getResultData(): array
    {
        return [];
    }
}
