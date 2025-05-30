<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;
use App\Data\Prompts\PromptMessage;

/**
 * The server's response to a prompts/get request from the client.
 */
final class GetPromptResult extends Result
{
    /**
     * @param array<PromptMessage> $messages
     */
    public function __construct(
        private array $messages,
        private ?string $description = null,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    /**
     * @return array<PromptMessage>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    protected function getResultData(): array
    {
        $data = [
            'messages' => array_map(fn(PromptMessage $message) => $message->jsonSerialize(), $this->messages),
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        return $data;
    }
}
