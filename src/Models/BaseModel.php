<?php
namespace ORM\Models;

use ORM\Core\DataBase;

class BaseModel extends DataBase
{
	private $connect;

	public function __construct(){
		$this->connect = $this->connect();
	}

	public function all($select = ['*']){
		return 'get all';
	}

	public function findById($id){
		return 'get find by id';
	}

	public function get() {
		return 'get ';
	}

	private function _query($sql)
	{
		return mysqli_query($this->connect, $sql);
	}
}