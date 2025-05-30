<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;
use App\Data\Sampling\ModelPreferences;
use App\Data\Sampling\SamplingMessage;

/**
 * A request from the server to sample an LLM via the client.
 */
final class CreateMessageRequest extends Request
{
    /**
     * @param array<SamplingMessage> $messages
     * @param array<string>|null $stopSequences
     */
    public function __construct(
        private array $messages,
        private int $maxTokens,
        private ?ModelPreferences $modelPreferences = null,
        private ?string $systemPrompt = null,
        private ?string $includeContext = null,
        private ?float $temperature = null,
        private ?array $stopSequences = null,
        private ?object $metadata = null
    ) {
        $params = [
            'messages' => array_map(fn(SamplingMessage $msg) => $msg->jsonSerialize(), $this->messages),
            'maxTokens' => $this->maxTokens,
        ];

        if ($this->modelPreferences !== null) {
            $params['modelPreferences'] = $this->modelPreferences->jsonSerialize();
        }

        if ($this->systemPrompt !== null) {
            $params['systemPrompt'] = $this->systemPrompt;
        }

        if ($this->includeContext !== null) {
            $params['includeContext'] = $this->includeContext;
        }

        if ($this->temperature !== null) {
            $params['temperature'] = $this->temperature;
        }

        if ($this->stopSequences !== null) {
            $params['stopSequences'] = $this->stopSequences;
        }

        if ($this->metadata !== null) {
            $params['metadata'] = $this->metadata;
        }

        parent::__construct('sampling/createMessage', $params);
    }

    /**
     * @return array<SamplingMessage>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getMaxTokens(): int
    {
        return $this->maxTokens;
    }

    public function getModelPreferences(): ?ModelPreferences
    {
        return $this->modelPreferences;
    }

    public function getSystemPrompt(): ?string
    {
        return $this->systemPrompt;
    }

    public function getIncludeContext(): ?string
    {
        return $this->includeContext;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    /**
     * @return array<string>|null
     */
    public function getStopSequences(): ?array
    {
        return $this->stopSequences;
    }

    public function getMetadata(): ?object
    {
        return $this->metadata;
    }
}
