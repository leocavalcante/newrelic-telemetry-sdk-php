<?php declare(strict_types=1);

namespace NewRelic\Metric;

final class SummaryValue
{
    private int $count;
    /** @var int|float */
    private $sum;
    /** @var int|float */
    private $min;
    /** @var int|float */
    private $max;

    public function __construct(int $count, $sum, $min, $max)
    {
        $this->count = $count;
        $this->sum = $sum;
        $this->min = $min;
        $this->max = $max;
    }
}