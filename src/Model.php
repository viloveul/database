<?php

namespace Viloveul\Database;

use Viloveul\Database\DatabaseFactory;
use Viloveul\Database\Contracts\Model as IModel;
use Viloveul\Database\Contracts\Collection as ICollection;
use Viloveul\Database\Contracts\Connection as IConnection;

abstract class Model implements IModel
{
    /**
     * @var string
     */
    private $alias = 'tbl';

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var mixed
     */
    private $newRecord = true;

    /**
     * @var array
     */
    private $origins = [];

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
        $this->resetState();
        $this->clearAttributes();
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
        foreach ($this->getAttributes() as $key => $value) {
            if (array_key_exists($key, $this->attributes)) {
                unset($this->attributes[$key]);
            }
            if (array_key_exists($key, $this->origins)) {
                unset($this->origins[$key]);
            }
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
            $this->query = $this->connection()->newQueryBuilder();
            $this->query->setModel($this);
        }
        return $this->query->{$method}(...$args);
    }

    /**
     * @return mixed
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function getAttributes(): array
    {
        return array_merge($this->origins, $this->attributes);
    }

    /**
     * @return mixed
     */
    public function isNewRecord(): bool
    {
        return $this->newRecord !== false;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getAttributes();
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
                $this->forwardsCall('load', [$key]);
            }
        }
        if (array_key_exists($key, $this->attributes)) {
            $method = 'get' . ucfirst($key) . 'Attribute';
            if (method_exists($this, $method)) {
                return $this->{$method}();
            } else {
                return $this->attributes[$key];
            }
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        if (!($value instanceof IModel) && !($value instanceof ICollection)) {
            if (array_key_exists($key, $this->attributes) && !array_key_exists($key, $this->origins)) {
                $this->origins[$key] = $this->attributes[$key];
            } elseif (!array_key_exists($key, $this->origins)) {
                $this->origins[$key] = $value;
            }
        }
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
     * @return mixed
     */
    public function oldAttributes(): array
    {
        return $this->origins;
    }

    public function primary()
    {
        return 'id';
    }

    public function relations(): array
    {
        return [];
    }

    public function resetState(): void
    {
        $this->query = null;
        $this->alias = 'tbl';
        $this->newRecord = false;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this[trim($key, '`"')] = $value;
        }
    }
}
