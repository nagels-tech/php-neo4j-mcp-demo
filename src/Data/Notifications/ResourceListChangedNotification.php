<?php

declare(strict_types=1);

namespace App\Data\Notifications;

use App\Data\Base\Notification;

/**
 * An optional notification from the server to the client, informing it that the list of resources it can read from has changed.
 */
final class ResourceListChangedNotification extends Notification
{
    public function __construct()
    {
        parent::__construct('notifications/resources/list_changed');
    }
}
