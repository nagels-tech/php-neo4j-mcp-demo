<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Base\Request;
use App\Data\Common\LoggingLevel;

/**
 * A request from the client to the server, to enable or adjust logging.
 */
final class SetLevelRequest extends Request
{
    public function __construct(private LoggingLevel $level)
    {
        parent::__construct('logging/setLevel', ['level' => $this->level->value]);
    }

    public function getLevel(): LoggingLevel
    {
        return $this->level;
    }
}
