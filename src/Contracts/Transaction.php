<?php

namespace Viloveul\Database\Contracts;

interface Transaction
{
    /**
     * @return mixed
     */
    public function commit(): bool;

    public function initialize(): void;

    /**
     * @return mixed
     */
    public function rollback(): bool;
}
