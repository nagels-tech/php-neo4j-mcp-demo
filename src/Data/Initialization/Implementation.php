<?php

declare(strict_types=1);

namespace App\Data\Initialization;

use JsonSerializable;

/**
 * Describes the name and version of an MCP implementation.
 */
final class Implementation implements JsonSerializable
{
    public function __construct(
        private string $name,
        private string $version
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
        ];
    }
}
