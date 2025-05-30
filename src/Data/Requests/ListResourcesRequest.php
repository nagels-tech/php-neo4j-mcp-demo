<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\PaginatedRequest;
use App\Data\Common\Cursor;

/**
 * Sent from the client to request a list of resources the server has.
 */
final class ListResourcesRequest extends PaginatedRequest
{
    public function __construct(?Cursor $cursor = null)
    {
        parent::__construct('resources/list', $cursor);
    }
}
