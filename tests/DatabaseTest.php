<?php declare(strict_types=1);
namespace ORM\Tests;

use PHPUnit\Framework\TestCase;
use ORM\Models\Blog;
use ORM\Models\Comment;
use ORM\Core\Mysql;
use ORM\Core\Postgresql;

final class DatabaseTest extends TestCase
{
    public function testCanBeConnectToMysql(): void
    {
        $host = 'localhost';
        $user = 'root';
        $pass = '123';
        $db = 'mydb';
        Mysql::connect($host, $user, $pass, $db);
        $this->assertEquals(Mysql::$dbConnectionStatus, Mysql::DATABASE_CONNECTION_OK);
    }

    public function testCanBeConnectToPostgresql(): void
    {
        $host = 'localhost';
        $user = 'root';
        $pass = '123';
        $db = 'mydb';
        Postgresql::connect($host, $user, $pass, $db);
        $this->assertEquals(Postgresql::$dbConnectionStatus, Postgresql::DATABASE_CONNECTION_OK);
    }
}
