<?php

namespace Viloveul\Database;

use Viloveul\Database\Contracts\Manager as IManager;
use Viloveul\Database\Contracts\Connection as IConnection;

class Manager implements IManager
{
    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @param IConnection $connection
     * @param string      $name
     */
    public function addConnection(IConnection $connection, string $name = 'default'): void
    {
        $this->connections[$name] = $connection;
    }

    /**
     * @param  string  $name
     * @return mixed
     */
    public function getConnection(string $name = 'default'): IConnection
    {
        return $this->connections[$name];
    }
}
