<?php

namespace Viloveul\Database\Contracts;

interface Condition
{
    /**
     * @param $expression
     * @param int           $operator
     * @param int           $separator
     */
    public function add($expression, int $operator, int $separator): void;

    public function all(): array;

    public function clear(): void;

    public function push(array $condition): void;
}
