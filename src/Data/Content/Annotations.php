<?php

declare(strict_types=1);

namespace App\Data\Content;

use App\Data\Common\Role;
use JsonSerializable;

/**
 * Optional annotations for the client. The client can use annotations to inform how objects are used or displayed
 */
final class Annotations implements JsonSerializable
{
    /**
     * @param array<Role>|null $audience
     * @param float|null $priority
     */
    public function __construct(
        private ?array $audience = null,
        private ?float $priority = null
    ) {}

    /**
     * @return array<Role>|null
     */
    public function getAudience(): ?array
    {
        return $this->audience;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->audience !== null) {
            $data['audience'] = array_map(fn(Role $role) => $role->value, $this->audience);
        }

        if ($this->priority !== null) {
            $data['priority'] = $this->priority;
        }

        return $data;
    }
}
