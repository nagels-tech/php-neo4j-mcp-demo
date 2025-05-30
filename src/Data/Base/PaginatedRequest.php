<?php

declare(strict_types=1);

namespace App\Data\Base;

use App\Data\Common\Cursor;

abstract class PaginatedRequest extends Request
{
    public function __construct(
        string $method,
        ?Cursor $cursor = null,
        ?array $additionalParams = null
    ) {
        $params = $additionalParams ?? [];

        if ($cursor !== null) {
            $params['cursor'] = $cursor->getValue();
        }

        parent::__construct($method, empty($params) ? null : $params);
    }
}
