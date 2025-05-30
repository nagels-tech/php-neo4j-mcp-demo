<?php

declare(strict_types=1);

namespace App\Data\JsonRpc;

use App\Data\Common\JSONRPCMessage;

/**
 * A JSON-RPC batch request, as described in https://www.jsonrpc.org/specification#batch.
 */
final class JSONRPCBatchRequest implements JSONRPCMessage
{
    /**
     * @param array<JSONRPCRequest|JSONRPCNotification> $requests
     */
    public function __construct(
        private array $requests
    ) {}

    /**
     * @return array<JSONRPCRequest|JSONRPCNotification>
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    public function jsonSerialize(): array
    {
        return array_map(fn($request) => $request->jsonSerialize(), $this->requests);
    }
}
