<?php
namespace ORM\Core;

class ConnectData
{
    public static function connect()
    {
        switch (Config::DB_CONNECTION) {
            case 'mysql':
                return Mysql::connect(Config::HOST, Config::USERNAME, Config::PASSWORD, Config::DB_NAME);
                break;
            case 'pgsql':
                return Postgresql::connect(Config::HOST, Config::USERNAME, Config::PASSWORD, Config::DB_NAME);
                break;
            default:
                return null;
                break;
        }
    }
}
