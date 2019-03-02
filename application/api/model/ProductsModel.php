<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class ProductsModel extends Model
{
    protected $table = 'products';


    /**
     * 根据id获取用户信息
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     */
    public function getOne($id)
    {
//        $r = $this->where('id', $id)->where('is_deleted','0')->find();
        $r = $this
            ->alias("p")
            ->join("products_cate c","c.id = p.cate_id",'LEFT')
            ->where("p.id",$id)
            ->where("p.is_deleted",'0')
            ->field("p.*,c.name as cate_name")
            ->find();
        return $r->toArray();
    }

    /**
     * 根据id获取用户信息
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     */
    public function getInfo($id)
    {
//        $r = $this->where('id', $id)->where('is_deleted','0')->find();
        $r = $this
            ->alias("p")
            ->join("products_cate c","c.id = p.cate_id",'LEFT')
            ->where("p.id",$id)
            ->field("p.*,c.name as cate_name")
            ->find();
        return $r->toArray();
    }

    /**
     * 根据id字符串查询用户信息
     * @param $ids
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getByIds($ids)
    {
//        $r = $this->where("id in ($ids)")->where('is_deleted','0')->select();
        if(!$ids){
            return [];
        }
        $r = $this
            ->alias("p")
            ->join("products_cate c","c.id = p.cate_id",'LEFT')
            ->where("p.id in ($ids)")
            ->where("p.is_deleted",'0')
            ->field("p.*,c.name as cate_name")
            ->select();
        return $r->toArray();
    }


}