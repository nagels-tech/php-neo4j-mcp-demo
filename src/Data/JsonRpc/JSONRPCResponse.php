<?php

declare(strict_types=1);

namespace App\Data\JsonRpc;

use App\Data\Base\Result;
use App\Data\Common\Constants;
use App\Data\Common\JSONRPCMessage;
use App\Data\Common\RequestId;

/**
 * A successful (non-error) response to a request.
 */
final class JSONRPCResponse implements JSONRPCMessage
{
    public function __construct(
        private RequestId $id,
        private Result $result
    ) {}

    public function getId(): RequestId
    {
        return $this->id;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $this->id->getValue(),
            'result' => $this->result->jsonSerialize(),
        ];
    }
}
