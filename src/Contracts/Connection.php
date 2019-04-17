<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\Compiler;
use Viloveul\Database\Contracts\Condition;
use Viloveul\Database\Contracts\QueryBuilder;

interface Connection
{
    public function commit(): bool;

    public function connect(): void;

    public function disconnect(): void;

    /**
     * @param string $query
     * @param array  $params
     */
    public function execute(string $query, array $params);

    public function inTransaction(): bool;

    public function isConnected(): bool;

    public function newCompiler(QueryBuilder $builder): Compiler;

    public function newCondition(QueryBuilder $builder, Compiler $compiler): Condition;

    public function newQueryBuilder(): QueryBuilder;

    /**
     * @param string $query
     */
    public function prepare(string $query): string;

    public function rollback(): bool;

    /**
     * @return mixed
     */
    public function showLogQueries(): array;

    public function transaction(): bool;
}
