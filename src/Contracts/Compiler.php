<?php

namespace Viloveul\Database\Contracts;

interface Compiler
{
    /**
     * @param array $conditions
     */
    public function buildCondition(array $conditions): string;

    /**
     * @param array $groups
     */
    public function buildGroupBy(array $groups): string;

    /**
     * @param array $orders
     */
    public function buildOrderBy(array $orders): string;

    /**
     * @param array $selectedColumns
     */
    public function buildSelectedColumn(array $selectedColumns): string;

    /**
     * @param string $column
     */
    public function makeColumnAlias(string $column): string;

    /**
     * @param array $params
     */
    public function makeParams(array $params): array;

    /**
     * @param string $column
     */
    public function normalizeColumn(string $column): string;

    /**
     * @param string $name
     * @param array  $relations
     */
    public function parseRelations(string $name, array $relations): array;
}
