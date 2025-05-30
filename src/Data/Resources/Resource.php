<?php

declare(strict_types=1);

namespace App\Data\Resources;

use App\Data\Content\Annotations;
use JsonSerializable;

/**
 * A known resource that the server is capable of reading.
 */
final class Resource implements JsonSerializable
{
    public function __construct(
        private string $uri,
        private string $name,
        private ?string $description = null,
        private ?string $mimeType = null,
        private ?Annotations $annotations = null,
        private ?int $size = null
    ) {}

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getAnnotations(): ?Annotations
    {
        return $this->annotations;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'uri' => $this->uri,
            'name' => $this->name,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->mimeType !== null) {
            $data['mimeType'] = $this->mimeType;
        }

        if ($this->annotations !== null) {
            $data['annotations'] = $this->annotations->jsonSerialize();
        }

        if ($this->size !== null) {
            $data['size'] = $this->size;
        }

        return $data;
    }
}
