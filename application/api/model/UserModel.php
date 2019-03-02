<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class UserModel extends Model
{
    protected $table = 'app_users';


    /**
     * 根据条件获取用户信息
     * @param $where
     * @return array|null|\PDOStatement|string|Model
     */
    public function getOne($where)
    {
        return $this->where($where)->find();
    }

    /**
     * 根据id字符串查询用户信息
     * @param $ids
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getByIds($ids)
    {
        return $this->where("id in ($ids)")->select();
    }


}