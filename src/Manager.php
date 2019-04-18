<?php

namespace Viloveul\Database;

use RuntimeException;
use Viloveul\Database\Contracts\Manager as IManager;
use Viloveul\Database\Contracts\Connection as IConnection;

class Manager implements IManager
{
    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @var mixed
     */
    protected $loaded = false;

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
        if ($this->loaded !== true) {
            throw new RuntimeException("Database manager is not loaded.");
        }
        if ($this->connections[$name]->isConnected() === false) {
            $this->connections[$name]->connect();
        }
        return $this->connections[$name];
    }

    public function load(): void
    {
        $this->loaded = true;
    }
}
