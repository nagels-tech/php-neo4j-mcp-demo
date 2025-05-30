<?php

declare(strict_types=1);

namespace App\Data\Resources;

final class BlobResourceContents extends ResourceContents
{
    public function __construct(
        string $uri,
        private string $blob,
        ?string $mimeType = null
    ) {
        parent::__construct($uri, $mimeType);
    }

    public function getBlob(): string
    {
        return $this->blob;
    }

    protected function getContentData(): array
    {
        return ['blob' => $this->blob];
    }
}
