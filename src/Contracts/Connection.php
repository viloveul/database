<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\QueryBuilder;

interface Connection
{
    /**
     * @param string $query
     */
    public function compile(string $query): string;

    public function connect(): void;

    public function disconnect(): void;

    public function isConnected(): bool;

    public function newQuery(): QueryBuilder;

    /**
     * @param string $identifier
     */
    public function prep(string $identifier): string;

    /**
     * @param string $query
     * @param array  $params
     */
    public function runCommand(string $query, array $params);
}
