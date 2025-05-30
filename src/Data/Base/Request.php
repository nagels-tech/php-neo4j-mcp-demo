<?php

declare(strict_types=1);

namespace App\Data\Base;

use App\Data\Common\ProgressToken;
use JsonSerializable;

abstract class Request implements JsonSerializable
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

    public function setProgressToken(ProgressToken $progressToken): void
    {
        if ($this->params === null) {
            $this->params = [];
        }

        if (!isset($this->params['_meta'])) {
            $this->params['_meta'] = [];
        }

        $this->params['_meta']['progressToken'] = $progressToken->getValue();
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
