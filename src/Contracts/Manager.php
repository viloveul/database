<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\Connection;

interface Manager
{
    /**
     * @param Connection $connection
     * @param string     $name
     */
    public function addConnection(Connection $connection, string $name = 'default'): void;

    public function all(): array;

    /**
     * @param string $name
     */
    public function getConnection(string $name = 'default'): Connection;

    public function load(): void;
}
