<?php

declare(strict_types=1);

namespace App\Data\Common;

use JsonSerializable;

/**
 * A uniquely identifying ID for a request in JSON-RPC.
 */
final class RequestId implements JsonSerializable
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
