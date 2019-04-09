<?php

namespace Viloveul\Database\Contracts;

use Viloveul\Database\Contracts\Model;
use Viloveul\Database\Contracts\Query;

interface Connection
{
    public function getPrefix(): string;

    /**
     * @param Model $model
     */
    public function newQuery(Model $model): Query;

    /**
     * @param string $query
     * @param array  $params
     */
    public function runCommand(string $query, array $params);

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix);
}
