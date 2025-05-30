<?php

declare(strict_types=1);

namespace App\Data\Tools;

use JsonSerializable;

/**
 * Additional properties describing a Tool to clients.
 */
final class ToolAnnotations implements JsonSerializable
{
    public function __construct(
        private ?string $title = null,
        private ?bool $readOnlyHint = null,
        private ?bool $destructiveHint = null,
        private ?bool $idempotentHint = null,
        private ?bool $openWorldHint = null
    ) {}

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getReadOnlyHint(): ?bool
    {
        return $this->readOnlyHint;
    }

    public function getDestructiveHint(): ?bool
    {
        return $this->destructiveHint;
    }

    public function getIdempotentHint(): ?bool
    {
        return $this->idempotentHint;
    }

    public function getOpenWorldHint(): ?bool
    {
        return $this->openWorldHint;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->title !== null) {
            $data['title'] = $this->title;
        }

        if ($this->readOnlyHint !== null) {
            $data['readOnlyHint'] = $this->readOnlyHint;
        }

        if ($this->destructiveHint !== null) {
            $data['destructiveHint'] = $this->destructiveHint;
        }

        if ($this->idempotentHint !== null) {
            $data['idempotentHint'] = $this->idempotentHint;
        }

        if ($this->openWorldHint !== null) {
            $data['openWorldHint'] = $this->openWorldHint;
        }

        return $data;
    }
}
