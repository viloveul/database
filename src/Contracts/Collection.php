<?php

namespace Viloveul\Database\Contracts;

use JsonSerializable;
use IteratorAggregate;

interface Collection extends IteratorAggregate, JsonSerializable
{
    public function all(): array;

    public function toArray(): array;
}
