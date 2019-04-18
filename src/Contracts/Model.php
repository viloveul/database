<?php

namespace Viloveul\Database\Contracts;

use ArrayAccess;
use JsonSerializable;
use Viloveul\Database\Contracts\Connection;

interface Model extends ArrayAccess, JsonSerializable
{
    const HAS_MANY = 11;

    const HAS_ONE = 12;

    public function afterFind(): void;

    public function afterSave(): void;

    public function beforeFind(): void;

    public function beforeSave(): void;

    public function clearAttributes(): void;

    public function connection(): Connection;

    public function getAlias(): string;

    public function getAttributes(): array;

    public function isAttributeCount(string $key): bool;

    public function isNewRecord(): bool;

    public function newInstance(): self;

    public function oldAttributes(): array;

    public function primary();

    public function relations(): array;

    public function resetState(): void;

    public function setAlias(string $alias): void;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    public function table(): string;

    public function toArray(): array;
}
