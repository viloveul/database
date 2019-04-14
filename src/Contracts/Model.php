<?php

namespace Viloveul\Database\Contracts;

use ArrayAccess;
use JsonSerializable;
use Viloveul\Database\Contracts\Connection;

interface Model extends ArrayAccess, JsonSerializable
{
    const HAS_MANY = 11;

    const HAS_ONE = 12;

    public function clearAttributes(): void;

    public function connection(): Connection;

    public function getAttributes(): array;

    public function relations(): array;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    public function table(): string;
}
