<?php declare(strict_types=1);

namespace NewRelic\Util;

function current_timestamp(): int
{
    return (int) round(microtime(true) * 1000);
}
