<?php

declare(strict_types=1);

namespace App\Data\Content;

use JsonSerializable;

/**
 * Audio provided to or from an LLM.
 */
final class AudioContent implements JsonSerializable
{
    public function __construct(
        private string $data,
        private string $mimeType,
        private ?Annotations $annotations = null
    ) {}

    public function getData(): string
    {
        return $this->data;
    }

    public function getMimeType(): string
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
            'type' => 'audio',
            'data' => $this->data,
            'mimeType' => $this->mimeType,
        ];

        if ($this->annotations !== null) {
            $data['annotations'] = $this->annotations->jsonSerialize();
        }

        return $data;
    }
}
