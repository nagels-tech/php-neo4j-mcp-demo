<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\PaginatedResult;
use App\Data\Common\Cursor;
use App\Data\Resources\Resource;

/**
 * The server's response to a resources/list request from the client.
 */
final class ListResourcesResult extends PaginatedResult
{
    /**
     * @param array<Resource> $resources
     */
    public function __construct(
        private array $resources,
        ?Cursor $nextCursor = null,
        ?array $_meta = null
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    /**
     * @return array<Resource>
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    protected function getPaginatedData(): array
    {
        return [
            'resources' => array_map(fn(Resource $resource) => $resource->jsonSerialize(), $this->resources),
        ];
    }
}
