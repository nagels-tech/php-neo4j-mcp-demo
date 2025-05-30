<?php

declare(strict_types=1);

namespace App\Data\Initialization;

use JsonSerializable;

/**
 * Present if the server offers any resources to read.
 */
final class ResourcesCapability implements JsonSerializable
{
    public function __construct(
        private ?bool $subscribe = null,
        private ?bool $listChanged = null
    ) {}

    public function getSubscribe(): ?bool
    {
        return $this->subscribe;
    }

    public function getListChanged(): ?bool
    {
        return $this->listChanged;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->subscribe !== null) {
            $data['subscribe'] = $this->subscribe;
        }

        if ($this->listChanged !== null) {
            $data['listChanged'] = $this->listChanged;
        }

        return $data;
    }
}
