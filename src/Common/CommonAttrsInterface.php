<?php declare(strict_types=1);

namespace NewRelic\Common;

interface CommonAttrsInterface
{
    public function getCommonAttrs(): array;
    public function setCommonAttrs(array $attributes): self;
}