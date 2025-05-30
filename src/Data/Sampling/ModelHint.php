<?php

declare(strict_types=1);

namespace App\Data\Sampling;

use JsonSerializable;

/**
 * Hints to use for model selection.
 */
final class ModelHint implements JsonSerializable
{
    public function __construct(
        private ?string $name = null
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        return $data;
    }
}
