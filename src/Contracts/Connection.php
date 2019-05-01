<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\Query;
use Viloveul\Database\Contracts\Result;
use Viloveul\Database\Contracts\Schema;

interface Connection
{
    public function commit(): bool;

    public function connect(): void;

    public function disconnect(): void;

    public function execute(string $query, array $params): Result;

    public function getDbHost(): string;

    public function getDbName(): string;

    public function getDbPort(): int;

    public function getPrefix(): string;

    public function inTransaction(): bool;

    public function isConnected(): bool;

    public function lastInsertedValue(string $name);

    public function makeAliasColumn(string $column, string $append): string;

    public function makeNormalizeColumn(string $column, string $table): string;

    public function newQuery(): Query;

    public function newSchema(string $name, array $options): Schema;

    public function prepare(string $query): string;

    public function quote(string $identifier): string;

    public function rollback(): bool;

    public function showLogQueries(): array;

    public function transaction(): bool;
}
