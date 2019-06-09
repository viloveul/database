<?php

namespace Viloveul\Database;

use Closure;
use Viloveul\Database\NoRecordException;
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
     * @param  array   $attributes
     * @return mixed
     */
    public function findOrCreate(array $attributes): IModel
    {
        $model = $this->findOrNew($attributes);
        if ($model->isNewRecord()) {
            $model->save();
        }
        return $model;
    }

    /**
     * @return mixed
     */
    public function findOrFail()
    {
        if ($model = $this->find()) {
            return $model;
        }
        throw new NoRecordException("No Record");
    }

    /**
     * @param  array   $attributes
     * @return mixed
     */
    public function findOrNew(array $attributes): IModel
    {
        if ($model = $this->find()) {
            return $model;
        } else {
            $model = $this->getModel()->newInstance();
            $model->setAttributes($attributes);
            return $model;
        }
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

    public function initialize(): void
    {
        $this->whereCondition = $this->newCondition();
        $this->havingCondition = $this->newCondition();
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
     * @param  $name
     * @param  Closure $callback
     * @return mixed
     */
    public function with($name, Closure $callback = null): IQuery
    {
        if (!is_scalar($name)) {
            foreach ($name as $key => $value) {
                if (is_callable($value)) {
                    $this->with($key, $value);
                } else {
                    $this->with($value);
                }
            }
        } else {
            $this->withRelations[$name] = $callback === null ? $name : $callback;
        }
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
