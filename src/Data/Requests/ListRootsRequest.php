<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;

/**
 * Sent from the server to request a list of root URIs from the client.
 */
final class ListRootsRequest extends Request
{
    public function __construct()
    {
        parent::__construct('roots/list');
    }
}
