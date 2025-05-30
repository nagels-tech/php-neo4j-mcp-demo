<?php

declare(strict_types=1);

namespace App\Data\Resources;

use JsonSerializable;

/**
 * The contents of a specific resource or sub-resource.
 */
abstract class ResourceContents implements JsonSerializable
{
    public function __construct(
        protected string $uri,
        protected ?string $mimeType = null
    ) {}

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function jsonSerialize(): array
    {
        $data = ['uri' => $this->uri];

        if ($this->mimeType !== null) {
            $data['mimeType'] = $this->mimeType;
        }

        return array_merge($data, $this->getContentData());
    }

    abstract protected function getContentData(): array;
}
