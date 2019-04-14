<?php

namespace Viloveul\Database\Contracts;

use Closure;
use Countable;
use Viloveul\Database\Contracts\Model;
use Viloveul\Database\Contracts\Collection;

interface QueryBuilder extends Countable
{
    const OPERATOR_BEETWEN = 21;

    const OPERATOR_EQUAL = 22;

    const OPERATOR_GT = 23;

    const OPERATOR_GTE = 24;

    const OPERATOR_IN = 25;

    const OPERATOR_LIKE = 26;

    const OPERATOR_LLIKE = 27;

    const OPERATOR_LT = 28;

    const OPERATOR_LTE = 29;

    const OPERATOR_NOT_IN = 30;

    const OPERATOR_RANGE = 31;

    const OPERATOR_RLIKE = 32;

    const ORDER_ASC = 77;

    const ORDER_DESC = 78;

    const SEPARATOR_AND = 98;

    const SEPARATOR_OR = 99;

    public function getModel(): Model;

    public function getParams(): array;

    public function getQuery(): string;

    public function getResult();

    public function getResults(): Collection;

    /**
     * @param int $size
     * @param int $offset
     */
    public function limit(int $size, int $offset): self;

    /**
     * @param string $column
     */
    public function max(string $column): int;

    /**
     * @param string $column
     */
    public function min(string $column): int;

    /**
     * @param string $order
     * @param int    $sort
     */
    public function orderBy(string $order, int $sort): self;

    /**
     * @param array $columns
     */
    public function select(array $columns = []): self;

    public function setModel(Model $model): void;

    /**
     * @param string   $column
     * @param $value
     * @param int      $operator
     * @param int      $separator
     */
    public function where(string $column, $value, int $operator, int $separator): self;

    /**
     * @param Closure $callback
     * @param int     $separator
     */
    public function whereGroup(Closure $callback, int $separator): self;

    public function with(string $name, Closure $callback): self;
}
