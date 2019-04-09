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

    public static function instance(): IManager
    {
        if (!(static::$dbman instanceof IManager)) {
            static::$dbman = new Manager();
        }
        return static::$dbman;
    }
}
