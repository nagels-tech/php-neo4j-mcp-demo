<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\PaginatedResult;
use App\Data\Common\Cursor;
use App\Data\Tools\Tool;

/**
 * The server's response to a tools/list request from the client.
 */
final class ListToolsResult extends PaginatedResult
{
    /**
     * @param array<Tool> $tools
     */
    public function __construct(
        private array $tools,
        ?Cursor $nextCursor = null,
        ?array $_meta = null
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    /**
     * @return array<Tool>
     */
    public function getTools(): array
    {
        return $this->tools;
    }

    protected function getPaginatedData(): array
    {
        return [
            'tools' => array_map(fn(Tool $tool) => $tool->jsonSerialize(), $this->tools),
        ];
    }
}
