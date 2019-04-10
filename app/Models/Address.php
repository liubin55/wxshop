<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'address';
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $primaryKey = 'address_id';
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
