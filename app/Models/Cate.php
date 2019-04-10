<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'category';
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $primaryKey = 'cate_id';
}
