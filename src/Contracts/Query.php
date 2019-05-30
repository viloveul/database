<?php

namespace Viloveul\Database\Contracts;

use Closure;
use Countable;
use Viloveul\Database\Contracts\Model;
use Viloveul\Database\Contracts\Query;
use Viloveul\Database\Contracts\Collection;
use Viloveul\Database\Contracts\Connection;

interface Query extends Countable
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

    const SEPARATOR_AND = 98;

    const SEPARATOR_OR = 99;

    const SORT_ASC = 100;

    const SORT_DESC = 101;

    const SYNC_ATTACH = 201;

    const SYNC_BOTH = 202;

    const SYNC_DETACH = 203;

    public function count(): int;

    public function delete();

    public function getCompiledGroupBy(): string;

    public function getCompiledHaving(): string;

    public function getCompiledOrderBy(): string;

    public function getCompiledSelect(): string;

    public function getCompiledTable(): string;

    public function getCompiledWhere(): string;

    public function getConnection(): Connection;

    public function getModel(): Model;

    public function getParams(): array;

    public function getQuery(bool $compile): string;

    public function find();

    public function findOrCreate(array $attributes): Model;

    public function findOrNew(array $attributes): Model;

    public function findAll(): Collection;

    public function value(string $column, $default);

    public function groupBy(string $column): Query;

    public function having($expression, int $operator, int $separator): Query;

    public function initialize(): void;

    public function join(string $name, array $conditions, string $joinType, array $throughs): Query;

    public function limit(int $size, int $offset): Query;

    public function load(string $name, Closure $callback): void;

    public function max(string $column);

    public function min(string $column);

    public function orWhere($expression, int $operator): Query;

    public function orWhereHas(string $relation, Closure $callback): Query;

    public function orderBy(string $column, int $sort): Query;

    public function parseRelations(string $name, array $relations): array;

    public function save(): Model;

    public function select($column, string $alias): Query;

    public function setConnection(Connection $connection): void;

    public function setModel(Model $model): void;

    public function sync(string $name, array $values, int $mode): void;

    public function throughConditions(): array;

    public function usingAggregate(): void;

    public function where($expression, int $operator, int $separator): Query;

    public function whereHas(string $name, Closure $callback, int $separator): Query;

    public function with($name, Closure $callback): Query;

    public function withCount(string $name, Closure $callback): Query;
}
