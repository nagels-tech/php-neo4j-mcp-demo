<?php

declare(strict_types=1);

namespace App\Data\Common;

use JsonSerializable;

/**
 * A progress token, used to associate progress notifications with the original request.
 */
final class ProgressToken implements JsonSerializable
{
    public function __construct(
        private string|int $value
    ) {}

    public function getValue(): string|int
    {
        return $this->value;
    }

    public function jsonSerialize(): string|int
    {
        return $this->value;
    }
}
