<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;
use App\Data\Initialization\ClientCapabilities;
use App\Data\Initialization\Implementation;

/**
 * This request is sent from the client to the server when it first connects, asking it to begin initialization.
 */
final class InitializeRequest extends Request
{
    public function __construct(
        private string $protocolVersion,
        private ClientCapabilities $capabilities,
        private Implementation $clientInfo
    ) {
        parent::__construct('initialize', [
            'protocolVersion' => $this->protocolVersion,
            'capabilities' => $this->capabilities->jsonSerialize(),
            'clientInfo' => $this->clientInfo->jsonSerialize(),
        ]);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function getCapabilities(): ClientCapabilities
    {
        return $this->capabilities;
    }

    public function getClientInfo(): Implementation
    {
        return $this->clientInfo;
    }
}
