<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\PaginatedResult;
use App\Data\Common\Cursor;
use App\Data\Prompts\Prompt;

/**
 * The server's response to a prompts/list request from the client.
 */
final class ListPromptsResult extends PaginatedResult
{
    /**
     * @param array<Prompt> $prompts
     */
    public function __construct(
        private array $prompts,
        ?Cursor $nextCursor = null,
        ?array $_meta = null
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    /**
     * @return array<Prompt>
     */
    public function getPrompts(): array
    {
        return $this->prompts;
    }

    protected function getPaginatedData(): array
    {
        return [
            'prompts' => array_map(fn(Prompt $prompt) => $prompt->jsonSerialize(), $this->prompts),
        ];
    }
}
