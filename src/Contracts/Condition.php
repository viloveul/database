<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\Query;

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

    public function getCompiled(): string;

    public function getQuery(): Query;

    public function push(array $condition): void;

    public function setQuery(Query $query);
}
