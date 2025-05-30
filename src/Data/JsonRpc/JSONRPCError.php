<?php

declare(strict_types=1);

namespace App\Data\JsonRpc;

use App\Data\Common\Constants;
use App\Data\Common\JSONRPCMessage;
use App\Data\Common\RequestId;

/**
 * A response to a request that indicates an error occurred.
 */
final class JSONRPCError implements JSONRPCMessage
{
    public function __construct(
        private RequestId $id,
        private int $code,
        private string $message,
        private mixed $data = null
    ) {}

    public function getId(): RequestId
    {
        return $this->id;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        $error = [
            'code' => $this->code,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $error['data'] = $this->data;
        }

        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $this->id->getValue(),
            'error' => $error,
        ];
    }
}
