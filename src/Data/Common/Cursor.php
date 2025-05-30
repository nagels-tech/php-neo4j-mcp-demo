<?php

declare(strict_types=1);

namespace App\Data\Common;

use JsonSerializable;

/**
 * An opaque token used to represent a cursor for pagination.
 */
final class Cursor implements JsonSerializable
{
    public function __construct(
        private string $value
    ) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
