<?php
namespace ORM\Models;

class Comment extends BaseModel
{
    protected $tableName = 'comments';

    public function blog()
    {
    	return $this->belongsTo('ORM\Models\Blog', 'id', 'target_id');
    }
}
