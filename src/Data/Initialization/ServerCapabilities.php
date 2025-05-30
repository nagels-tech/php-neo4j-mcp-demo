<?php

declare(strict_types=1);

namespace App\Data\Initialization;

use JsonSerializable;

/**
 * Capabilities that a server may support. Known capabilities are defined here, in this schema, but this is not a closed set: any server can define its own, additional capabilities.
 */
final class ServerCapabilities implements JsonSerializable
{
    public function __construct(
        private ?array $experimental = null,
        private ?object $logging = null,
        private ?object $completions = null,
        private ?PromptsCapability $prompts = null,
        private ?ResourcesCapability $resources = null,
        private ?ToolsCapability $tools = null
    ) {}

    public function getExperimental(): ?array
    {
        return $this->experimental;
    }

    public function getLogging(): ?object
    {
        return $this->logging;
    }

    public function getCompletions(): ?object
    {
        return $this->completions;
    }

    public function getPrompts(): ?PromptsCapability
    {
        return $this->prompts;
    }

    public function getResources(): ?ResourcesCapability
    {
        return $this->resources;
    }

    public function getTools(): ?ToolsCapability
    {
        return $this->tools;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->experimental !== null) {
            $data['experimental'] = $this->experimental;
        }

        if ($this->logging !== null) {
            $data['logging'] = $this->logging;
        }

        if ($this->completions !== null) {
            $data['completions'] = $this->completions;
        }

        if ($this->prompts !== null) {
            $data['prompts'] = $this->prompts->jsonSerialize();
        }

        if ($this->resources !== null) {
            $data['resources'] = $this->resources->jsonSerialize();
        }

        if ($this->tools !== null) {
            $data['tools'] = $this->tools->jsonSerialize();
        }

        return $data;
    }
}
