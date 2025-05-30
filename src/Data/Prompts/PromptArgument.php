<?php

declare(strict_types=1);

namespace App\Data\Prompts;

use JsonSerializable;

/**
 * Describes an argument that a prompt can accept.
 */
final class PromptArgument implements JsonSerializable
{
    public function __construct(
        private string $name,
        private ?string $description = null,
        private ?bool $required = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function jsonSerialize(): array
    {
        $data = ['name' => $this->name];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->required !== null) {
            $data['required'] = $this->required;
        }

        return $data;
    }
}
