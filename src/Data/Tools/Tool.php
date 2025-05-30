<?php

declare(strict_types=1);

namespace App\Data\Tools;

use JsonSerializable;

/**
 * Definition for a tool the client can call.
 */
final class Tool implements JsonSerializable
{
    public function __construct(
        private string $name,
        private array $inputSchema,
        private ?string $description = null,
        private ?ToolAnnotations $annotations = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getInputSchema(): array
    {
        return $this->inputSchema;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAnnotations(): ?ToolAnnotations
    {
        return $this->annotations;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
            'inputSchema' => $this->inputSchema,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->annotations !== null) {
            $data['annotations'] = $this->annotations->jsonSerialize();
        }

        return $data;
    }
}
