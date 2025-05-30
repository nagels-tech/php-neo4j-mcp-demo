<?php

declare(strict_types=1);

namespace App\Data\Base;

use JsonSerializable;

abstract class Result implements JsonSerializable
{
    public function __construct(
        private ?array $_meta = null
    ) {}

    public function getMeta(): ?array
    {
        return $this->_meta;
    }

    public function setMeta(array $meta): void
    {
        $this->_meta = $meta;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->_meta !== null) {
            $data['_meta'] = $this->_meta;
        }

        return array_merge($data, $this->getResultData());
    }

    abstract protected function getResultData(): array;
}
