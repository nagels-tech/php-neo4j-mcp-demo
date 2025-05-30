<?php

declare(strict_types=1);

namespace App\Data\JsonRpc;

use App\Data\Common\JSONRPCMessage;

/**
 * A JSON-RPC batch response, as described in https://www.jsonrpc.org/specification#batch.
 */
final class JSONRPCBatchResponse implements JSONRPCMessage
{
    /**
     * @param array<JSONRPCResponse|JSONRPCError> $responses
     */
    public function __construct(
        private array $responses
    ) {}

    /**
     * @return array<JSONRPCResponse|JSONRPCError>
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    public function jsonSerialize(): array
    {
        return array_map(fn($response) => $response->jsonSerialize(), $this->responses);
    }
}
