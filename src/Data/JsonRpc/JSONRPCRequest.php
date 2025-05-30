<?php

declare(strict_types=1);

namespace App\Data\JsonRpc;

use App\Data\Base\Request;
use App\Data\Common\Constants;
use App\Data\Common\JSONRPCMessage;
use App\Data\Common\RequestId;

/**
 * A request that expects a response.
 */
final class JSONRPCRequest extends Request implements JSONRPCMessage
{
    public function __construct(
        private RequestId $id,
        string $method,
        ?array $params = null
    ) {
        parent::__construct($method, $params);
    }

    public function getId(): RequestId
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $this->id->getValue(),
        ]);
    }
}
