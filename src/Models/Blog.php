<?php
namespace ORM\Models;

class Blog extends BaseModel
{
    protected $tableName = 'blogs';

    public function comments()
    {
        return $this->hasMany('ORM\Models\Comment', 'target_id');
    }
}
