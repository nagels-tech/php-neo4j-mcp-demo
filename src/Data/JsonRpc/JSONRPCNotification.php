<?php

declare(strict_types=1);

namespace App\Data\JsonRpc;

use App\Data\Base\Notification;
use App\Data\Common\Constants;
use App\Data\Common\JSONRPCMessage;

/**
 * A notification which does not expect a response.
 */
final class JSONRPCNotification extends Notification implements JSONRPCMessage
{
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'jsonrpc' => Constants::JSONRPC_VERSION,
        ]);
    }
}
