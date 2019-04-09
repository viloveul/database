<?php

namespace Viloveul\Database\Contracts;

use Closure;
use Countable;

interface Query extends Countable
{
    /**
     * @param string $column
     */
    public function max(string $column): int;

    /**
     * @param string $column
     */
    public function min(string $column): int;

    /**
     * @param array $columns
     */
    public function select(array $columns = []): self;

    /**
     * @param string   $column
     * @param string   $operator
     * @param $value
     * @param string   $separator
     */
    public function where(string $column, string $operator, $value, string $separator = 'AND'): self;

    /**
     * @param Closure $callback
     * @param string  $separator
     */
    public function whereGroup(Closure $callback, string $separator = 'AND'): self;
}
