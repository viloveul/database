<?php

namespace Viloveul\Database\Contracts;

interface Result
{
    public function fetchAll(): array;

    public function fetchOne();

    /**
     * @param int        $column
     * @param $default
     */
    public function fetchScalar(int $column, $default);
}
