<?php

declare(strict_types=1);

namespace App\Data\Content;

use App\Data\Resources\BlobResourceContents;
use App\Data\Resources\TextResourceContents;
use JsonSerializable;

/**
 * The contents of a resource, embedded into a prompt or tool call result.
 */
final class EmbeddedResource implements JsonSerializable
{
    public function __construct(
        private TextResourceContents|BlobResourceContents $resource,
        private ?Annotations $annotations = null
    ) {}

    public function getResource(): TextResourceContents|BlobResourceContents
    {
        return $this->resource;
    }

    public function getAnnotations(): ?Annotations
    {
        return $this->annotations;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'type' => 'resource',
            'resource' => $this->resource->jsonSerialize(),
        ];

        if ($this->annotations !== null) {
            $data['annotations'] = $this->annotations->jsonSerialize();
        }

        return $data;
    }
}
