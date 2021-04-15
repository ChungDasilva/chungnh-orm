<?php
namespace ORM\Models;

use ORM\Core\DataBase;

class BaseModel extends DataBase
{
    private $sql;

    public function __construct()
    {
        $this->connect();
    }

    public static function __callStatic($method, $params)
    {
        return (new static)->$method(...$params);
    }

    protected function all($select = ['*'])
    {
        $columns = implode(',', $select);
        $tableName = $this->tableName;
        $this->sql = "SELECT $columns FROM $tableName;";
        return $this->get();
    }

    protected function find($id)
    {
        $tableName = $this->tableName;
        $this->sql = "SELECT * FROM $tableName WHERE id = $id";
        return $this->get();
    }

    protected function Where(...$params)
    {
        $tableName = $this->tableName;
        switch (count($params)) {
            case 3:
                $this->sql = "SELECT * FROM $tableName WHERE $params[0] $params[1] '$params[2]';";
                break;
            case 2:
                $this->sql = "SELECT * FROM $tableName WHERE $params[0] = '$params[1]';";
                break;
            case 1:
                if (is_array($params)) {
                    $this->sql = "SELECT * FROM $tableName WHERE";
                    foreach ($params[0] as $key => $param) {
                        foreach ($param as $key2 => $value) {
                            if ($key2 < 2) {
                                $this->sql .= " $value";
                            } else {
                                $this->sql .= " '$value' AND";
                            }
                        }
                    }
                    $this->sql = rtrim($this->sql, " AND");
                }
                break;
        }
        return $this;
    }

    protected function hasMany($model, $column, $id = 'id')
    {
        $modelObj = new $model;
        $tableName = $modelObj->tableName;
    	$id = $this->{$id};
        $modelObj->sql = "SELECT * FROM $tableName  WHERE $column = $id;";
        return $modelObj->get();
    }

    protected function belongsTo($model, $column, $id = 'id')
    {
    	$modelObj = new $model;
        $tableName = $modelObj->tableName;
        $id = $this->{$id};
        $modelObj->sql = "SELECT * FROM $tableName  WHERE $column = $id;";
        return $modelObj->get();
    }

    public function get()
    {
        $result = $this->query($this->sql);
        $list = [];
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $ref = new \ReflectionClass($this);
                $tableObj = $ref->newInstance();
                $tableObj->cast($obj);
                $list[] = $tableObj;
            }
        }

        if (count($list) == 1) {
            return $list[0];
        }
        return $list;
    }

    public function cast($object)
    {
        if (is_array($object) || is_object($object)) {
            foreach ($object as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}
