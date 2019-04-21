<?php

namespace Viloveul\Database;

use Closure;
use Viloveul\Database\Contracts\Model as IModel;
use Viloveul\Database\Contracts\Query as IQuery;
use Viloveul\Database\Contracts\Condition as ICondition;
use Viloveul\Database\Contracts\Connection as IConnection;

abstract class Query implements IQuery
{
    /**
     * @var array
     */
    protected $bindParams = [];

    /**
     * @var mixed
     */
    protected $havingCondition;

    /**
     * @var mixed
     */
    protected $whereCondition;

    /**
     * @var array
     */
    protected $withCounts = [];

    /**
     * @var array
     */
    protected $withRelations = [];

    /**
     * @var mixed
     */
    private $connection;

    /**
     * @var mixed
     */
    private $model;

    public function __destruct()
    {
        $this->connection = null;
        $this->whereCondition = null;
        $this->havingCondition = null;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getQuery();
    }

    /**
     * @return mixed
     */
    public function getConnection(): IConnection
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    public function getModel(): IModel
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams(): array
    {
        return $this->bindParams;
    }

    /**
     * @param  array   $conditions
     * @param  array   $attributes
     * @return mixed
     */
    public function getResultOrCreate(array $conditions, array $attributes = []): IModel
    {
        $model = $this->getResultOrInstance($conditions, $attributes);
        if ($model->isNewRecord()) {
            $model->save();
        }
        return $model;
    }

    /**
     * @param  array   $conditions
     * @param  array   $attributes
     * @return mixed
     */
    public function getResultOrInstance(array $conditions, array $attributes = []): IModel
    {
        $this->whereCondition->clear();
        if ($model = $this->where($conditions)->getResult()) {
            return $model;
        } else {
            $model = $this->getModel()->newInstance();
            $model->setAttributes($attributes);
            $model->setAttributes($conditions);
            return $model;
        }
    }

    public function initialize(): void
    {
        $this->whereCondition = $this->newCondition();
        $this->havingCondition = $this->newCondition();
    }

    /**
     * @param  array   $columns
     * @return mixed
     */
    public function multipleSelect(array $columns): IQuery
    {
        foreach ($columns as $key => $value) {
            if (is_numeric($key)) {
                $this->select($value);
            } else {
                $this->select($value, $key);
            }
        }
        return $this;
    }

    /**
     * @param  array   $relations
     * @return mixed
     */
    public function multipleWith(array $relations): IQuery
    {
        foreach ($relations as $key => $value) {
            if (is_callable($value)) {
                $this->with($key, $value);
            } else {
                $this->with($value);
            }
        }
        return $this;
    }

    abstract public function newCondition(): ICondition;

    public function setConnection(IConnection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param IModel $model
     */
    public function setModel(IModel $model): void
    {
        $this->model = $model;
    }

    /**
     * @param  string  $name
     * @param  Closure $callback
     * @return mixed
     */
    public function with(string $name, Closure $callback = null): IQuery
    {
        $this->withRelations[$name] = $callback === null ? $name : $callback;
        return $this;
    }

    /**
     * @param  string  $name
     * @param  Closure $callback
     * @return mixed
     */
    public function withCount(string $name, Closure $callback = null): IQuery
    {
        $this->withCounts[$name] = $callback === null ? $name : $callback;
        return $this;
    }
}
