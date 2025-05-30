<?php

declare(strict_types=1);

namespace App\Data\Common;

use JsonSerializable;

/**
 * Refers to any valid JSON-RPC object that can be decoded off the wire, or encoded to be sent.
 */
interface JSONRPCMessage extends JsonSerializable {}
