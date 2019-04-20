<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\Query;
use Viloveul\Database\Contracts\Schema;
use Viloveul\Database\Contracts\Compiler;
use Viloveul\Database\Contracts\Condition;

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

    public function getDbHost(): string;

    public function getDbName(): string;

    public function getDbPort(): int;

    public function getPrefix(): string;

    public function inTransaction(): bool;

    public function isConnected(): bool;

    public function newCompiler(Query $query): Compiler;

    public function newCondition(Query $query): Condition;

    public function newQuery(): Query;

    public function newSchema(string $name, array $options): Schema;

    /**
     * @param string $query
     */
    public function prepare(string $query): string;

    public function quote(string $identifier): string;

    public function rollback(): bool;

    /**
     * @return mixed
     */
    public function showLogQueries(): array;

    public function transaction(): bool;
}
