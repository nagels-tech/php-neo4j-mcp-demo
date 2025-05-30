<?php

declare(strict_types=1);

namespace App\Data\Resources;

final class TextResourceContents extends ResourceContents
{
    public function __construct(
        string $uri,
        private string $text,
        ?string $mimeType = null
    ) {
        parent::__construct($uri, $mimeType);
    }

    public function getText(): string
    {
        return $this->text;
    }

    protected function getContentData(): array
    {
        return ['text' => $this->text];
    }
}
