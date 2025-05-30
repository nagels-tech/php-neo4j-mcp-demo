<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;
use App\Data\Content\AudioContent;
use App\Data\Content\EmbeddedResource;
use App\Data\Content\ImageContent;
use App\Data\Content\TextContent;

/**
 * The server's response to a tool call.
 */
final class CallToolResult extends Result
{
    /**
     * @param array<TextContent|ImageContent|AudioContent|EmbeddedResource> $content
     */
    public function __construct(
        private array $content,
        private ?bool $isError = null,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    /**
     * @return array<TextContent|ImageContent|AudioContent|EmbeddedResource>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    public function getIsError(): ?bool
    {
        return $this->isError;
    }

    protected function getResultData(): array
    {
        $data = [
            'content' => array_map(fn($item) => $item->jsonSerialize(), $this->content),
        ];

        if ($this->isError !== null) {
            $data['isError'] = $this->isError;
        }

        return $data;
    }
}
