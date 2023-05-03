<?php
namespace Core;

use App\Config;
use PDO;

/**
 * Base model.
 */
abstract class Model
{
    private static $db = null;

    /**
     * Get the PDO database connection.
     *
     * @return mixed
     */
    protected static function getDB()
    {
        if (self::$db === null)
        {
            $host = Config::DB_HOST;
            $dbname = Config::DB_NAME;
            $username = Config::DB_USERNAME;
            $password = Config::DB_PASSWORD;

            try
            {
                self::$db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            }
            catch (\PDOException $e)
            {
                throw new \Exception($e->getMessage(), 500);
            }
        }

        return self::$db;
    }
}