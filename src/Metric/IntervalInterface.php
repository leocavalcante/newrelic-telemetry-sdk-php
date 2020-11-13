<?php declare(strict_types=1);

namespace NewRelic\Metric;

interface IntervalInterface
{
    public function getIntervalMs(): int;
}