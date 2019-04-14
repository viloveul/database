<?php

namespace Viloveul\Database;

use Viloveul\Database\Manager;
use Viloveul\Database\Contracts\Manager as IManager;

class DatabaseFactory
{
    /**
     * @var mixed
     */
    protected static $dbman = null;

    /**
     * @param array $connections
     */
    public static function instance(array $connections = []): IManager
    {
        if (!(static::$dbman instanceof IManager)) {
            static::$dbman = new Manager();
            foreach ($connections as $key => $value) {
                static::$dbman->addConnection($value, $key);
            }
        }
        return static::$dbman;
    }
}
