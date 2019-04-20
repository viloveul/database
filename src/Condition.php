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
    protected $query;

    /**
     * @param IQuery $query
     */
    public function __construct(IQuery $query)
    {
        $this->query = $query;
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
     * @param array $condition
     */
    public function push(array $condition): void
    {
        $this->conditions[] = $condition;
    }
}
