<?php declare(strict_types=1);

namespace NewRelic\Metric;

/**
 * @extends Metric<int|float>
 */
final class Gauge extends Metric
{
    private const TYPE = 'gauge';

    protected function getType(): string
    {
        return self::TYPE;
    }
}
