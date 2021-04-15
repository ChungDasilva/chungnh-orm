<?php
namespace ORM\Models;

class Blog extends BaseModel
{
    protected $tableName = 'blogs';

    public function comments()
    {
        return $this->hasMany('comments', 'target_id');
    }
}
