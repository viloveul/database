<?php

namespace Viloveul\Database\Contracts;

use Closure;
use Countable;
use Viloveul\Database\Contracts\Model;
use Viloveul\Database\Contracts\Collection;
use Viloveul\Database\Contracts\Expression;

interface QueryBuilder extends Countable
{
    const CONDITION_HAVING = 11;

    const CONDITION_WHERE = 12;

    const OPERATOR_BEETWEN = 21;

    const OPERATOR_EQUAL = 22;

    const OPERATOR_GT = 23;

    const OPERATOR_GTE = 24;

    const OPERATOR_IN = 25;

    const OPERATOR_LIKE = 26;

    const OPERATOR_LT = 27;

    const OPERATOR_LTE = 28;

    const OPERATOR_NOT_IN = 29;

    const OPERATOR_RANGE = 30;

    const ORDER_ASC = 77;

    const ORDER_DESC = 78;

    const SEPARATOR_AND = 98;

    const SEPARATOR_OR = 99;

    /**
     * @param $value
     */
    public function addParam($value): string;

    public function delete();

    public function getModel(): Model;

    public function getParams(): array;

    public function getQuery(): string;

    public function getResult();

    public function getResults(): Collection;

    public function groupBy(string $column): self;

    /**
     * @param int $size
     * @param int $offset
     */
    public function limit(int $size, int $offset): self;

    public function load(string $name, Closure $callback): void;

    /**
     * @param string $column
     */
    public function max(string $column);

    /**
     * @param string $column
     */
    public function min(string $column);

    public function orWhere($expression, int $operator): self;

    public function orWhereHas(string $name, Closure $callback): self;

    /**
     * @param string $order
     * @param int    $sort
     */
    public function orderBy(string $order, int $sort): self;

    /**
     * @param string $identifier
     */
    public function quote(string $identifier): string;

    public function save();

    public function select(string $column, string $alias): self;

    public function setModel(Model $model): void;

    /**
     * @param $expression
     * @param int           $operator
     * @param int           $separator
     */
    public function where($expression, int $operator, int $separator): self;

    public function whereHas(string $name, Closure $callback, int $separator): self;

    public function with(string $name, Closure $callback): self;
}
