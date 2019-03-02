<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class TrafficRecordModel extends Model
{
    protected $table = 'traffic_record';

    public function get_list($contact_id){
        return $this->where('contact_id',$contact_id)->order("create_at desc")->select();
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
     * 获取退订描述
     * @return array
     */
    public function get_cancel_desc()
    {
        return $this->cancel_desc;
    }

}