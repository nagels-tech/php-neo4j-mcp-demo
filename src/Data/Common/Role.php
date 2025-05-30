<?php

declare(strict_types=1);

namespace App\Data\Common;

/**
 * The sender or recipient of messages and data in a conversation.
 */
enum Role: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
}
