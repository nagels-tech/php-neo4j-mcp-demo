<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;
use App\Data\Roots\Root;

/**
 * The client's response to a roots/list request from the server.
 */
final class ListRootsResult extends Result
{
    /**
     * @param array<Root> $roots
     */
    public function __construct(
        private array $roots,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    /**
     * @return array<Root>
     */
    public function getRoots(): array
    {
        return $this->roots;
    }

    protected function getResultData(): array
    {
        return [
            'roots' => array_map(fn(Root $root) => $root->jsonSerialize(), $this->roots),
        ];
    }
}
