<?php

namespace Viloveul\Database;

use Closure;
use Viloveul\Database\Contracts\Model as IModel;
use Viloveul\Database\Contracts\Query as IQuery;
use Viloveul\Database\Contracts\Compiler as ICompiler;
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
    protected $compiler;

    /**
     * @var mixed
     */
    protected $connection;

    /**
     * @var mixed
     */
    protected $havingCondition;

    /**
     * @var array
     */
    protected $mapThroughConditions = [];

    /**
     * @var mixed
     */
    protected $model;

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
     * @param IConnection $connection
     */
    public function __construct(IConnection $connection)
    {
        $this->connection = $connection;
        $this->compiler = $connection->newCompiler($this);
        $this->whereCondition = $connection->newCondition($this);
        $this->havingCondition = $connection->newCondition($this);
    }

    public function __destruct()
    {
        $this->whereCondition->clear();
        $this->havingCondition->clear();
        $this->connection = null;
        $this->compiler = null;
        $this->whereCondition = null;
        $this->havingCondition = null;
    }

    /**
     * @param  $value
     * @return mixed
     */
    public function addParam($value): string
    {
        $key = ':bind_' . $this->getModel()->getAlias() . '_' . count($this->bindParams);
        $this->bindParams[$key] = $value;
        return $key;
    }

    /**
     * @return mixed
     */
    public function getCompiler(): ICompiler
    {
        return $this->compiler;
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
     * @param string  $name
     * @param Closure $callback
     */
    public function load(string $name, Closure $callback = null): void
    {
        if ($rel = $this->getCompiler()->parseRelations($name, $this->getModel()->relations())) {
            [$type, $class, $through, $keys, $use] = $rel;
            $model = new $class();
            $model->setAlias($name);
            if ($through !== null) {
                $model->join($through, $keys, 'inner', $this->getModel()->relations());
                $keys = $model->throughConditions();
            }
            $model->where(function (ICondition $where) use ($keys) {
                foreach ($keys as $parent => $child) {
                    $where->add([$child => $this->getModel()->{$parent}]);
                }
            });

            is_callable($use) and $use($model);
            is_callable($callback) and $callback($model);

            $model->beforeFind();

            if ($type === IModel::HAS_MANY) {
                $this->getModel()->setAttributes([$name => $model->getResults()]);
            } else {
                $this->getModel()->setAttributes([$name => $model->getResult()]);
            }

            $model->afterFind();
        }
        $this->getModel()->resetState();
    }

    /**
     * @param IModel $model
     */
    public function setModel(IModel $model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function throughConditions(): array
    {
        return $this->mapThroughConditions;
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
