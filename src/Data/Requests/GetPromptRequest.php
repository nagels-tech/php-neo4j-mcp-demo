<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;

/**
 * Used by the client to get a prompt provided by the server.
 */
final class GetPromptRequest extends Request
{
    public function __construct(
        private string $name,
        private ?array $arguments = null
    ) {
        $params = ['name' => $this->name];

        if ($this->arguments !== null) {
            $params['arguments'] = $this->arguments;
        }

        parent::__construct('prompts/get', $params);
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
