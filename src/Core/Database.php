<?php
namespace ORM\Core;

class Database
{
    const HOST = 'localhost';
    const DB_NAME = 'mydb';
    const USERNAME = 'root';
    const PASSWORD = '123';

    private static $connect;

    public function connect()
    {
        if (self::$connect === NULL)
        {
            self::$connect = mysqli_connect(self::HOST, self::USERNAME, self::PASSWORD, self::DB_NAME);
            mysqli_set_charset(self::$connect, 'UTF8');
            if (mysqli_connect_errno() === 0) {
                return self::$connect;
            }
            return false;
        }

        return self::$connect;
    }

    protected function query($sql)
    {
        return mysqli_query(self::$connect, $sql);
    }
}


