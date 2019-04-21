<?php

namespace Viloveul\Database;

use Viloveul\Database\Contracts\Query as IQuery;
use Viloveul\Database\Contracts\Condition as ICondition;

abstract class Condition implements ICondition
{
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var mixed
     */
    private $query;

    public function __destruct()
    {
        $this->clear();
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->conditions;
    }

    public function clear(): void
    {
        foreach ($this->conditions as $key => $value) {
            $this->conditions[$key] = null;
            unset($this->conditions[$key]);
        }
        $this->conditions = [];
    }

    /**
     * @return mixed
     */
    public function getQuery(): IQuery
    {
        return $this->query;
    }

    /**
     * @param array $condition
     */
    public function push(array $condition): void
    {
        $this->conditions[] = $condition;
    }

    /**
     * @param IQuery $query
     */
    public function setQuery(IQuery $query)
    {
        $this->query = $query;
    }
}
