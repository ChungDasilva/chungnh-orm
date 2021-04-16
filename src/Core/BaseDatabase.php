<?php
namespace ORM\Core;

abstract class BaseDatabase
{
    abstract public static function connect($host, $user, $pass, $db);
    abstract public function connectionStatus();
    abstract public function query($sql);
}
