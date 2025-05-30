<?php

declare(strict_types=1);

namespace App\Data\Notifications;

use App\Data\Base\Notification;
use App\Data\Common\RequestId;

/**
 * This notification can be sent by either side to indicate that it is cancelling a previously-issued request.
 */
final class CancelledNotification extends Notification
{
    public function __construct(
        private RequestId $requestId,
        private ?string $reason = null
    ) {
        $params = ['requestId' => $this->requestId->getValue()];

        if ($this->reason !== null) {
            $params['reason'] = $this->reason;
        }

        parent::__construct('notifications/cancelled', $params);
    }

    public function getRequestId(): RequestId
    {
        return $this->requestId;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
