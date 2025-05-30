<?php

declare(strict_types=1);

namespace App\Data\Prompts;

use App\Data\Common\Role;
use App\Data\Content\AudioContent;
use App\Data\Content\EmbeddedResource;
use App\Data\Content\ImageContent;
use App\Data\Content\TextContent;
use JsonSerializable;

/**
 * Describes a message returned as part of a prompt.
 */
final class PromptMessage implements JsonSerializable
{
    public function __construct(
        private Role $role,
        private TextContent|ImageContent|AudioContent|EmbeddedResource $content
    ) {}

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getContent(): TextContent|ImageContent|AudioContent|EmbeddedResource
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
