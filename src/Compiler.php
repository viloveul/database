<?php

namespace Viloveul\Database;

use Viloveul\Database\Contracts\Query as IQuery;
use Viloveul\Database\Contracts\Compiler as ICompiler;
use Viloveul\Database\Contracts\Connection as IConnection;

abstract class Compiler implements ICompiler
{
    /**
     * @var mixed
     */
    protected $builder;

    /**
     * @var mixed
     */
    protected $connection;

    /**
     * @param IConnection $connection
     * @param IQuery      $builder
     */
    public function __construct(IConnection $connection, IQuery $builder)
    {
        $this->builder = $builder;
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function buildCondition(array $conditions): string
    {
        $condition = '';
        foreach ($conditions as $value) {
            if (!empty($condition)) {
                $condition .= ' ' . ($value['separator'] === IQuery::SEPARATOR_AND ? 'AND' : 'OR') . ' ';
            }
            $condition .= $value['argument'];
        }
        return $condition;
    }

    /**
     * @param array $groups
     */
    public function buildGroupBy(array $groups): string
    {
        return implode(', ', $groups);
    }

    /**
     * @param  array   $params
     * @return mixed
     */
    public function makeParams(array $params): array
    {
        $binds = [];
        foreach ($params as $value) {
            $binds[] = $this->builder->addParam($value);
        }
        return $binds;
    }

    /**
     * @param  string  $name
     * @param  array   $relations
     * @return mixed
     */
    public function parseRelations(string $name, array $relations): array
    {
        if (array_key_exists($name, $relations)) {
            $relation = $relations[$name];
            $def = ['type' => null, 'class' => null, 'through' => null, 'keys' => [], 'use' => null];
            $resolve = [];
            foreach ($def as $key => $value) {
                $resolve[] = array_key_exists($key, $relation) ? $relation[$key] : $value;
            }
            return $resolve;
        } else {
            return [];
        }
    }
}
