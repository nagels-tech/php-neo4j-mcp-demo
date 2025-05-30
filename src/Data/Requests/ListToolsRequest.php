<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\PaginatedRequest;
use App\Data\Common\Cursor;

/**
 * Sent from the client to request a list of tools the server has.
 */
final class ListToolsRequest extends PaginatedRequest
{
    public function __construct(?Cursor $cursor = null)
    {
        parent::__construct('tools/list', $cursor);
    }
}
