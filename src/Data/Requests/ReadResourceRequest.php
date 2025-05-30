<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;

/**
 * Sent from the client to the server, to read a specific resource URI.
 */
final class ReadResourceRequest extends Request
{
    public function __construct(private string $uri)
    {
        parent::__construct('resources/read', ['uri' => $this->uri]);
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
