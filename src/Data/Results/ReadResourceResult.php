<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;
use App\Data\Resources\BlobResourceContents;
use App\Data\Resources\TextResourceContents;

/**
 * The server's response to a resources/read request from the client.
 */
final class ReadResourceResult extends Result
{
    /**
     * @param array<TextResourceContents|BlobResourceContents> $contents
     */
    public function __construct(
        private array $contents,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    /**
     * @return array<TextResourceContents|BlobResourceContents>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    protected function getResultData(): array
    {
        return [
            'contents' => array_map(fn($content) => $content->jsonSerialize(), $this->contents),
        ];
    }
}
