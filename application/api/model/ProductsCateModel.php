<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class ProductsCateModel extends Model
{
    protected $table = 'products_cate';


    /**
     * 根据id获取用户信息
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     */
    public function getOne($id)
    {
        $r = $this->where('id', $id)->where('is_deleted','0')->find();

        return $r->toArray();
    }

    /**
     * 通过名称获取数量
     * @param $name
     * @return int|string
     */
    public function getCountByName($name){
        $r = $this->where('name', $name)->where('is_deleted','0')->count();
        return $r;
    }

    /**
     * 根据id字符串查询用户信息
     * @param $ids
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getByIds($ids)
    {
        $r = $this->where("id in ($ids)")->where('is_deleted','0')->column("id,name");

        return $r;
    }


}