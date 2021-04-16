<?php
namespace ORM\Core;

class Mysql extends BaseDatabase
{
    private static $connect;
    public static $dbConnectionStatus;

    public const DATABASE_CONNECTION_OK = 1;
    public const DATABASE_CONNECTION_FAILURE = 0;

    public static function connect($host, $user, $pass, $db)
    {
        if (self::$connect === null) {
            self::$connect = mysqli_connect($host, $user, $pass, $db);
            mysqli_set_charset(self::$connect, 'UTF8');
            if (mysqli_connect_errno() === 0) {
                self::$dbConnectionStatus = self::DATABASE_CONNECTION_OK;
            }
            self::$dbConnectionStatus = self::DATABASE_CONNECTION_FAILURE;
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
        $result = mysqli_query(self::$connect, $sql);
        $list = [];
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $list[] = $obj;
            }
        }

        return $list;
    }
}
