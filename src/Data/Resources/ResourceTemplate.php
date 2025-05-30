<?php

declare(strict_types=1);

namespace App\Data\Resources;

use App\Data\Content\Annotations;
use JsonSerializable;

/**
 * A template description for resources available on the server.
 */
final class ResourceTemplate implements JsonSerializable
{
    public function __construct(
        private string $uriTemplate,
        private string $name,
        private ?string $description = null,
        private ?string $mimeType = null,
        private ?Annotations $annotations = null
    ) {}

    public function getUriTemplate(): string
    {
        return $this->uriTemplate;
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

    public function jsonSerialize(): array
    {
        $data = [
            'uriTemplate' => $this->uriTemplate,
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

        return $data;
    }
}
