<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;
use service\DataService;

class ContactsProcessModel extends Model
{
    protected $table = 'contacts_process';


    /**
     * 根据id获取用户信息
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->where('is_deleted', '0')->find();
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
     * 根据contacts_id获取信息
     * @param $contacts_id
     * @return array|bool|null|\PDOStatement|string|Model
     */
    public function getOneByContacts_id($contacts_id)
    {
        $r =  $this->where('contacts_id', $contacts_id)->where('is_deleted', '0')->find();
        if(!$r){
            return false;
        }
        return $r;
    }

    /**
     * 保险上传
     * @param $savedata
     * @return bool
     */
    public function save_info($savedata)
    {
        if(!$savedata['contacts_id']){
            return false;
        }
        $r = DataService::save($this->table, $savedata, 'contacts_id');
        if($r){
            $info = $this->where("contacts_id",$savedata['contacts_id'])->find();
            return $info['id'];
        }else{
            return false;
        }
    }

}