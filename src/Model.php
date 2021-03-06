<?php

namespace Viloveul\Database;

use Viloveul\Database\DatabaseFactory;
use Viloveul\Database\Contracts\Model as IModel;
use Viloveul\Database\Contracts\Collection as ICollection;
use Viloveul\Database\Contracts\Connection as IConnection;

abstract class Model implements IModel
{
    /**
     * @var array
     */
    protected $protects = [];

    /**
     * @var string
     */
    private $alias = 'tbl';

    /**
     * @var array
     */
    private $attributeCounts = [];

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
        return call_user_func([static::newInstance(), 'forwardsCall'], $method, $args);
    }

    public function __clone()
    {
        $this->resetState();
        $this->clearAttributes();
    }

    /**
     * @return mixed
     */
    public function __construct()
    {
        $filterRelations = array_filter($this->relations(), function ($v) {
            return $v['type'] === static::HAS_MANY;
        });
        $relationKeys = array_keys($filterRelations);
        $this->attributeCounts = array_map(function ($attr) {
            return 'count_' . $attr;
        }, $relationKeys);
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

    public function afterFind(): void
    {
        // keep silence
    }

    public function afterSave(): void
    {
        // keep silence
    }

    public function beforeFind(): void
    {
        // keep silence
    }

    public function beforeSave(): void
    {
        // keep silence
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
        foreach ($this->attributeCounts as $key) {
            $this->attributes[$key] = 0;
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
        $attr = array_merge($this->origins, $this->attributes);
        $results = [];
        foreach ($attr as $key => $value) {
            $results[$key] = $this->offsetGet($key);
        }
        return $results;
    }

    /**
     * @param string $key
     */
    public function isAttributeCount(string $key): bool
    {
        return in_array($key, $this->attributeCounts);
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
        return $this->toArray();
    }

    public static function newInstance(): IModel
    {
        return new static();
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
                if (!$this->isAttributeCount($key)) {
                    $this->forwardsCall('load', [$key]);
                }
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
            if (!$this->isAttributeCount($key)) {
                if (array_key_exists($key, $this->attributes) && !array_key_exists($key, $this->origins)) {
                    $this->origins[$key] = $this->attributes[$key];
                } elseif (!array_key_exists($key, $this->origins)) {
                    $this->origins[$key] = $value;
                }
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

    /**
     * @return mixed
     */
    public function toArray(): array
    {
        $attr = array_filter($this->getAttributes(), function ($k) {
            return !in_array($k, $this->protects);
        }, ARRAY_FILTER_USE_KEY);

        return array_map(function ($v) {
            if (($v instanceof ICollection) || ($v instanceof IModel)) {
                return $v->toArray();
            }
            return $v;
        }, $attr);
    }
}
