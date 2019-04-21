<?php

namespace Viloveul\Database;

use Viloveul\Database\Contracts\Connection as IConnection;

abstract class Connection implements IConnection
{
    /**
     * @var mixed
     */
    protected $host;

    /**
     * @var mixed
     */
    protected $name = '';

    /**
     * @var mixed
     */
    protected $port = 3306;

    /**
     * @var mixed
     */
    protected $prefix = '';

    /**
     * @var array
     */
    private $logs = [];

    /**
     * @param string $query
     * @param array  $params
     */
    public function addLogQuery(string $query, array $params = []): void
    {
        $this->logs[] = compact('query', 'params');
    }

    /**
     * @return mixed
     */
    public function getDbHost(): string
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getDbName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDbPort(): int
    {
        return abs($this->port);
    }

    /**
     * @return mixed
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return mixed
     */
    public function showLogQueries(): array
    {
        return $this->logs;
    }
}
