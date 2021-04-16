<?php
namespace ORM\Core;

class Postgresql extends BaseDatabase
{
    private static $connect;
    public static $dbConnectionStatus;

    public const DATABASE_CONNECTION_OK = 1;
    public const DATABASE_CONNECTION_FAILURE = 0;

    public static function connect($host, $user, $pass, $db)
    {
        if (self::$connect === null) {
            self::$connect = pg_connect("host=".$host." dbname=".$db." user=".$user." password=".$pass);
            if (self::$connect) {
                self::$dbConnectionStatus = self::DATABASE_CONNECTION_OK;
            } else {
                self::$dbConnectionStatus = self::DATABASE_CONNECTION_FAILURE;
            }
        }
        self::$dbConnectionStatus = self::DATABASE_CONNECTION_OK;

        return new self;
    }

    public function connectionStatus()
    {
        return self::$dbConnectionStatus;
    }


    public function query($sql)
    {
        $result = pg_exec(self::$connect, $sql);
        if (pg_numrows($result) > 0) {
            return $result;
        } else {
            return [];
        }
    }
}
