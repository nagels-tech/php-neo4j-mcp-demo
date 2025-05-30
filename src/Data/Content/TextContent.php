<?php

declare(strict_types=1);

namespace App\Data\Content;

use JsonSerializable;

/**
 * Text provided to or from an LLM.
 */
final class TextContent implements JsonSerializable
{
    public function __construct(
        private string $text,
        private ?Annotations $annotations = null
    ) {}

    public function getText(): string
    {
        return $this->text;
    }

    public function getAnnotations(): ?Annotations
    {
        return $this->annotations;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'type' => 'text',
            'text' => $this->text,
        ];

        if ($this->annotations !== null) {
            $data['annotations'] = $this->annotations->jsonSerialize();
        }

        return $data;
    }
}
