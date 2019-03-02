<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class CityModel extends Model
{
    protected $table = 'city';


    /**
     * 根据id获取用户信息
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
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

    /**
     * 获取地址
     */
    public function get_city_info($id)
    {
        $city_info = '';
        $city = $this->getOne($id);
        if (!$city) {
            return $city_info;
        }
        $city_info = $city['name'];
        while ($city['parent_id'] != 1) {
            $city = $this->getOne($city['parent_id']);
            if (!$city) {
                return $city_info;
            }
            $city_info = $city['name'] . ' ' . $city_info;
        }
        return $city_info;
    }


}