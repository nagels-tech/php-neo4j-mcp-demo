<?php

declare(strict_types=1);

namespace App\Data\Notifications;

use App\Data\Base\Notification;
use App\Data\Common\ProgressToken;

/**
 * An out-of-band notification used to inform the receiver of a progress update for a long-running request.
 */
final class ProgressNotification extends Notification
{
    public function __construct(
        private ProgressToken $progressToken,
        private int $progress,
        private ?int $total = null,
        private ?string $message = null
    ) {
        $params = [
            'progressToken' => $this->progressToken->getValue(),
            'progress' => $this->progress,
        ];

        if ($this->total !== null) {
            $params['total'] = $this->total;
        }

        if ($this->message !== null) {
            $params['message'] = $this->message;
        }

        parent::__construct('notifications/progress', $params);
    }

    public function getProgressToken(): ProgressToken
    {
        return $this->progressToken;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
