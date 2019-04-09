<?php

namespace Viloveul\Database;

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
     * @param  $method
     * @param  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $query = $this->connection()->newQuery($this);
        return call_user_func_array([$query, $method], $args);
    }

    /**
     * @param $method
     * @param $args
     */
    public static function __callStatic($method, $args)
    {
        $class = get_called_class();
        $self = new $class();
        return call_user_func([$self, '__call'], $method, $args);
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
     * @return mixed
     */
    public function getAttributes(): array
    {
        return $this->attributes;
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
