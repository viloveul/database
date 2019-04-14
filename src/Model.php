<?php

namespace Viloveul\Database;

use Closure;
use Viloveul\Database\DatabaseFactory;
use Viloveul\Database\Contracts\Model as IModel;
use Viloveul\Database\Contracts\Connection as IConnection;

abstract class Model implements IModel
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var mixed
     */
    private $query = null;

    /**
     * @param  $method
     * @param  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->forwardsCall($method, $args);
    }

    /**
     * @param $method
     * @param $args
     */
    public static function __callStatic($method, $args)
    {
        $class = get_called_class();
        return call_user_func([new $class(), 'forwardsCall'], $method, $args);
    }

    public function __clone()
    {
        $this->clearAttributes();
        $this->query = null;
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    public function clearAttributes(): void
    {
        foreach ($this->attributes as $key => $value) {
            unset($this->attributes[$key]);
        }
    }

    public function connection(): IConnection
    {
        return DatabaseFactory::instance()->getConnection();
    }

    /**
     * @param string $method
     * @param array  $args
     */
    public function forwardsCall(string $method, array $args)
    {
        if ($this->query === null) {
            $this->query = $this->connection()->newQuery();
            $this->query->setModel($this);
        }
        return call_user_func_array([$this->query, $method], $args);
    }

    /**
     * @return mixed
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getAttributes();
    }

    /**
     * @param string  $name
     * @param Closure $callback
     */
    public function load(string $name, Closure $callback = null): void
    {
        [$type, $class, $pk, $fk] = $this->relations()[$name];
        $params = array_key_exists(4, $this->relations()[$name]) ? $this->relations()[$name][4] : [];
        $model = $class::where($fk, $this->attributes[$pk]);
        if (array_key_exists('conditions', $params)) {
            foreach ($params['conditions'] as $condition) {
                $model->where(...$condition);
            }
        }
        is_callable($callback) and $callback($model);
        if ($type === static::HAS_MANY) {
            $this->attributes[$name] = $model->getResults();
        } else {
            $this->attributes[$name] = $model->getResult();
        }
    }

    /**
     * @param $key
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * @param $key
     */
    public function offsetGet($key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            if (array_key_exists($key, $this->relations())) {
                $this->load($key);
            }
        }
        return array_key_exists($key, $this->attributes) ? $this->attributes[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param $key
     */
    public function offsetUnset($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            unset($this->attributes[$key]);
        }
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }
}
