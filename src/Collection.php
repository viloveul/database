<?php

namespace Viloveul\Database;

use Closure;
use ArrayIterator;
use Viloveul\Database\Contracts\Model as IModel;
use Viloveul\Database\Contracts\Collection as ICollection;

class Collection implements ICollection
{
    /**
     * @var mixed
     */
    protected $model;

    /**
     * @var array
     */
    protected $relations = [];

    /**
     * @var array
     */
    protected $results = [];

    /**
     * @param  IModel  $model
     * @param  array   $results
     * @param  array   $relations
     * @return mixed
     */
    public function __construct(IModel $model, array $results, array $relations)
    {
        $this->model = $model;
        $this->results = array_values($results);
        $this->relations = $relations;
        $this->model->resetState();
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        $keys = array_keys($this->results);
        return array_map(function ($i) {
            return $this->one($i);
        }, $keys);
    }

    public function count(): int
    {
        return count($this->results);
    }

    /**
     * @param  Closure $callback
     * @return mixed
     */
    public function filter(Closure $callback): ICollection
    {
        $results = [];
        $keys = array_keys($this->results);
        foreach ($keys as $index) {
            $model = $this->one($index);
            if ($callback($model) === true) {
                $results[] = $model;
            }
        }
        $this->results = $results;
        return $this;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param  int     $index
     * @return mixed
     */
    public function one(int $index = 0)
    {
        if (array_key_exists($index, $this->results)) {
            if (!($this->results[$index] instanceof IModel)) {
                $new = clone $this->model;
                $new->setAttributes($this->results[$index]);
                foreach ($this->relations as $key => $relations) {
                    $rel = clone $relations['values'];
                    $maps = $relations['maps'];
                    $filter = $rel->filter(function ($model) use ($maps, $new) {
                        foreach ($maps as $pk => $fk) {
                            if ($new->{$pk} != $model->{$fk}) {
                                return false;
                            }
                        }
                        return true;
                    });
                    if ($relations['type'] === IModel::HAS_MANY) {
                        $new->setAttributes([
                            $key => $filter,
                        ]);
                    } else {
                        $new->setAttributes([
                            $key => $filter->one(),
                        ]);
                    }
                }
                $this->results[$index] = $new;
            }
            return $this->results[$index];
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function toArray(): array
    {
        return array_map(function (IModel $model) {
            return $model->toArray();
        }, $this->all());
    }
}
