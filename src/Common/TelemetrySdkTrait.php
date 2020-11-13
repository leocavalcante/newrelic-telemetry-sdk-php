<?php declare(strict_types=1);

namespace NewRelic\Common;

trait TelemetrySdkTrait
{
    protected array $commonAttrs = [];

    public function getCommonAttrs(): array
    {
        return array_merge([
            'telemetry.sdk.name' => 'newrelic-contrib',
            'telemetry.sdk.language' => 'php',
            'telemetry.sdk.version' => '0.1.0',
        ], $this->commonAttrs);
    }

    public function setCommonAttrs(array $attributes): self
    {
        $this->commonAttrs = $attributes;
        return $this;
    }
}