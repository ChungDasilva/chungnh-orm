<?php
namespace ORM\Core;

class Database
{
    const HOST = 'localhost';
    const DB_NAME = 'mvc';
    const USERNAME = 'root';
    const PASSWORD = '';

    private $connect;

    public function connect()
    {
        $this->connect = mysqli_connect(self::HOST, self::USERNAME, self::PASSWORD, self::DB_NAME);
        mysqli_set_charset($this->connect, 'UTF8');
        if (mysqli_connect_errno() === 0) {
            return $this->connect;
        }
        return false;
    }
}


