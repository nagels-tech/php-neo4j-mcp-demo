<?php

declare(strict_types=1);

namespace App\Data\Results;

use App\Data\Base\Result;
use App\Data\Common\Role;
use App\Data\Content\AudioContent;
use App\Data\Content\ImageContent;
use App\Data\Content\TextContent;

/**
 * The client's response to a sampling/create_message request from the server.
 */
final class CreateMessageResult extends Result
{
    public function __construct(
        private Role $role,
        private TextContent|ImageContent|AudioContent $content,
        private string $model,
        private ?string $stopReason = null,
        ?array $_meta = null
    ) {
        parent::__construct($_meta);
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getContent(): TextContent|ImageContent|AudioContent
    {
        return $this->content;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getStopReason(): ?string
    {
        return $this->stopReason;
    }

    protected function getResultData(): array
    {
        $data = [
            'role' => $this->role->value,
            'content' => $this->content->jsonSerialize(),
            'model' => $this->model,
        ];

        if ($this->stopReason !== null) {
            $data['stopReason'] = $this->stopReason;
        }

        return $data;
    }
}
