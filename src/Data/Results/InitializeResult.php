<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;
use App\Data\Initialization\Implementation;
use App\Data\Initialization\ServerCapabilities;

/**
 * After receiving an initialize request from the client, the server sends this response.
 */
final class InitializeResult extends Result
{
    public function __construct(
        private string $protocolVersion,
        private ServerCapabilities $capabilities,
        private Implementation $serverInfo,
        private ?string $instructions = null,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function getCapabilities(): ServerCapabilities
    {
        return $this->capabilities;
    }

    public function getServerInfo(): Implementation
    {
        return $this->serverInfo;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    protected function getResultData(): array
    {
        $data = [
            'protocolVersion' => $this->protocolVersion,
            'capabilities' => $this->capabilities->jsonSerialize(),
            'serverInfo' => $this->serverInfo->jsonSerialize(),
        ];

        if ($this->instructions !== null) {
            $data['instructions'] = $this->instructions;
        }

        return $data;
    }
}
