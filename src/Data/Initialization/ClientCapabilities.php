<?php

declare(strict_types=1);

namespace App\Data\Initialization;

use JsonSerializable;

/**
 * Capabilities a client may support. Known capabilities are defined here, in this schema, but this is not a closed set: any client can define its own, additional capabilities.
 */
final class ClientCapabilities implements JsonSerializable
{
    public function __construct(
        private ?array $experimental = null,
        private ?RootsCapability $roots = null,
        private ?object $sampling = null
    ) {}

    public function getExperimental(): ?array
    {
        return $this->experimental;
    }

    public function getRoots(): ?RootsCapability
    {
        return $this->roots;
    }

    public function getSampling(): ?object
    {
        return $this->sampling;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->experimental !== null) {
            $data['experimental'] = $this->experimental;
        }

        if ($this->roots !== null) {
            $data['roots'] = $this->roots->jsonSerialize();
        }

        if ($this->sampling !== null) {
            $data['sampling'] = $this->sampling;
        }

        return $data;
    }
}
