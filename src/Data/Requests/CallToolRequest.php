<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;

/**
 * Used by the client to invoke a tool provided by the server.
 */
final class CallToolRequest extends Request
{
    public function __construct(
        private string $name,
        private ?array $arguments = null
    ) {
        $params = ['name' => $this->name];

        if ($this->arguments !== null) {
            $params['arguments'] = $this->arguments;
        }

        parent::__construct('tools/call', $params);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArguments(): ?array
    {
        return $this->arguments;
    }
}
