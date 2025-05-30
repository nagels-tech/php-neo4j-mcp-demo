<?php

declare(strict_types=1);

namespace App\Data\Sampling;

use App\Data\Common\Role;
use App\Data\Content\AudioContent;
use App\Data\Content\ImageContent;
use App\Data\Content\TextContent;
use JsonSerializable;

/**
 * Describes a message issued to or received from an LLM API.
 */
final class SamplingMessage implements JsonSerializable
{
    public function __construct(
        private Role $role,
        private TextContent|ImageContent|AudioContent $content
    ) {}

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getContent(): TextContent|ImageContent|AudioContent
    {
        return $this->content;
    }

    public function jsonSerialize(): array
    {
        return [
            'role' => $this->role->value,
            'content' => $this->content->jsonSerialize(),
        ];
    }
}
