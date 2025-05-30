<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\PaginatedRequest;
use App\Data\Common\Cursor;

/**
 * Sent from the client to request a list of prompts and prompt templates the server has.
 */
final class ListPromptsRequest extends PaginatedRequest
{
    public function __construct(?Cursor $cursor = null)
    {
        parent::__construct('prompts/list', $cursor);
    }
}
