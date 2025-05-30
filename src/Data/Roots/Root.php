<?php

declare(strict_types=1);

namespace App\Data\Roots;

use JsonSerializable;

/**
 * Represents a root directory or file that the server can operate on.
 */
final class Root implements JsonSerializable
{
    public function __construct(
        private string $uri,
        private ?string $name = null
    ) {}

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        $data = ['uri' => $this->uri];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        return $data;
    }
}
