<?php

declare(strict_types=1);

namespace App\Data\Notifications;

use App\Data\Base\Notification;
use App\Data\Common\LoggingLevel;

/**
 * Notification of a log message passed from server to client.
 */
final class LoggingMessageNotification extends Notification
{
    public function __construct(
        private LoggingLevel $level,
        private mixed $data,
        private ?string $logger = null
    ) {
        $params = [
            'level' => $this->level->value,
            'data' => $this->data,
        ];

        if ($this->logger !== null) {
            $params['logger'] = $this->logger;
        }

        parent::__construct('notifications/message', $params);
    }

    public function getLevel(): LoggingLevel
    {
        return $this->level;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getLogger(): ?string
    {
        return $this->logger;
    }
}
