<?php

declare(strict_types=1);

namespace App\Data\Initialization;

use JsonSerializable;

/**
 * Present if the server offers any prompt templates.
 */
final class PromptsCapability implements JsonSerializable
{
    public function __construct(
        private ?bool $listChanged = null
    ) {}

    public function getListChanged(): ?bool
    {
        return $this->listChanged;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->listChanged !== null) {
            $data['listChanged'] = $this->listChanged;
        }

        return $data;
    }
}
