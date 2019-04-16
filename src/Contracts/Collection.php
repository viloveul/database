<?php

namespace Viloveul\Database\Contracts;

use Closure;
use Countable;
use JsonSerializable;
use IteratorAggregate;

interface Collection extends IteratorAggregate, JsonSerializable, Countable
{
    public function all(): array;

    public function filter(Closure $callback): self;

    public function one(int $index);

    public function toArray(): array;
}
