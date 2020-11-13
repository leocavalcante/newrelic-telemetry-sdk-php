<?php declare(strict_types=1);

namespace NewRelic\Metric;

/**
 * @extends Metric<int|float>
 */
final class Count extends Metric implements IntervalInterface
{
    private int $intervalMs = 0;

    protected function getType(): string
    {
        return 'count';
    }

    /**
     * @param int|float $delta
     * @return $this
     */
    public function record($delta = 1): self
    {
        $this->value += $delta;
        return $this;
    }

    public function getIntervalMs(): int
    {
        return $this->intervalMs;
    }

    public function setIntervalMs(int $intervalMs): self
    {
        $this->intervalMs = $intervalMs;
        return $this;
    }
}
