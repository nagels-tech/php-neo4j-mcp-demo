<?php

declare(strict_types=1);

namespace App\Data\Prompts;

use JsonSerializable;

/**
 * A prompt or prompt template that the server offers.
 */
final class Prompt implements JsonSerializable
{
    /**
     * @param array<PromptArgument>|null $arguments
     */
    public function __construct(
        private string $name,
        private ?string $description = null,
        private ?array $arguments = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array<PromptArgument>|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    public function jsonSerialize(): array
    {
        $data = ['name' => $this->name];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->arguments !== null) {
            $data['arguments'] = array_map(fn(PromptArgument $arg) => $arg->jsonSerialize(), $this->arguments);
        }

        return $data;
    }
}
