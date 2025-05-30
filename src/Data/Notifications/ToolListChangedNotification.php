<?php

declare(strict_types=1);

namespace App\Data\Notifications;

use App\Data\Base\Notification;

/**
 * An optional notification from the server to the client, informing it that the list of tools it offers has changed.
 */
final class ToolListChangedNotification extends Notification
{
    public function __construct()
    {
        parent::__construct('notifications/tools/list_changed');
    }
}
