<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submedia extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'submedia';
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */

    public $timestamps = false;
    /**
     * 关联到模型的数据表id
     *
     * @var string
     */
    protected $primaryKey ='id';
}
