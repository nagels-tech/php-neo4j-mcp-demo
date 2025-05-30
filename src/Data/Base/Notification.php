<?php

declare(strict_types=1);

namespace App\Data\Base;

use JsonSerializable;

abstract class Notification implements JsonSerializable
{
    public function __construct(
        private string $method,
        private ?array $params = null
    ) {}

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function jsonSerialize(): array
    {
        $data = ['method' => $this->method];

        if ($this->params !== null) {
            $data['params'] = $this->params;
        }

        return $data;
    }
}
