<?php

declare(strict_types=1);

namespace App\Data\Sampling;

use JsonSerializable;

/**
 * The server's preferences for model selection, requested of the client during sampling.
 */
final class ModelPreferences implements JsonSerializable
{
    /**
     * @param array<ModelHint>|null $hints
     */
    public function __construct(
        private ?array $hints = null,
        private ?float $costPriority = null,
        private ?float $speedPriority = null,
        private ?float $intelligencePriority = null
    ) {}

    /**
     * @return array<ModelHint>|null
     */
    public function getHints(): ?array
    {
        return $this->hints;
    }

    public function getCostPriority(): ?float
    {
        return $this->costPriority;
    }

    public function getSpeedPriority(): ?float
    {
        return $this->speedPriority;
    }

    public function getIntelligencePriority(): ?float
    {
        return $this->intelligencePriority;
    }

    public function jsonSerialize(): array
    {
        $data = [];

        if ($this->hints !== null) {
            $data['hints'] = array_map(fn(ModelHint $hint) => $hint->jsonSerialize(), $this->hints);
        }

        if ($this->costPriority !== null) {
            $data['costPriority'] = $this->costPriority;
        }

        if ($this->speedPriority !== null) {
            $data['speedPriority'] = $this->speedPriority;
        }

        if ($this->intelligencePriority !== null) {
            $data['intelligencePriority'] = $this->intelligencePriority;
        }

        return $data;
    }
}
