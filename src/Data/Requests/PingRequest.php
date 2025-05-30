<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;

/**
 * A ping, issued by either the server or the client, to check that the other party is still alive.
 */
final class PingRequest extends Request
{
    public function __construct()
    {
        parent::__construct('ping');
    }
}
