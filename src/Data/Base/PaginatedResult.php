<?php

declare(strict_types=1);

namespace App\Data\Base;

use App\Data\Common\Cursor;

abstract class PaginatedResult extends Result
{
    public function __construct(
        private ?Cursor $nextCursor = null,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    public function getNextCursor(): ?Cursor
    {
        return $this->nextCursor;
    }

    protected function getResultData(): array
    {
        $data = $this->getPaginatedData();

        if ($this->nextCursor !== null) {
            $data['nextCursor'] = $this->nextCursor->getValue();
        }

        return $data;
    }

    abstract protected function getPaginatedData(): array;
}
