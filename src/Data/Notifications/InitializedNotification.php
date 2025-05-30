<?php

declare(strict_types=1);

namespace App\Data\Notifications;

use App\Data\Base\Notification;

/**
 * This notification is sent from the client to the server after initialization has finished.
 */
final class InitializedNotification extends Notification
{
    public function __construct()
    {
        parent::__construct('notifications/initialized');
    }
}
