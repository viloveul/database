<?php

namespace Viloveul\Database;

use RuntimeException;
use BadMethodCallException;
use Viloveul\Database\DatabaseFactory;
use Viloveul\Database\Contracts\Connection as IConnection;
use Viloveul\Database\Contracts\Transaction as ITransaction;

class Transaction implements ITransaction
{
    /**
     * @var mixed
     */
    protected $connection = null;

    /**
     * @var mixed
     */
    private $initialized = false;

    /**
     * @param IConnection $connection
     */
    public function __construct(IConnection $connection = null)
    {
        $this->connection = $connection ? DatabaseFactory::instance()->getConnection() : $connection;
        $this->initialize();
    }

    /**
     * @return mixed
     */
    public function commit(): bool
    {
        if ($this->initialized !== true) {
            throw new RuntimeException("Transaction not initialized.");
        }
        return $this->connection->commit();
    }

    public function initialize(): void
    {
        if (!($this->connection instanceof IConnection)) {
            throw new BadMethodCallException("Connection invalid.");
        }
        $this->initialized = true;
    }

    /**
     * @return mixed
     */
    public function rollback(): bool
    {
        if ($this->initialized !== true) {
            throw new RuntimeException("Transaction not initialized.");
        }
        return $this->connection->rollback();
    }
}
