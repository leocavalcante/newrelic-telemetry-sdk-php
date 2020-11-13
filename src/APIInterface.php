<?php declare(strict_types=1);

namespace NewRelic;

interface APIInterface
{
    public function commit(): APIResponseInterface;
}
