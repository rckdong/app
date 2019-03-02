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

class ContactsCheckModel extends Model
{
    protected $table = 'contacts_check';


    /**
     * 根据contacts_id获取信息
     * @param $contacts_id
     * @return array|bool|null|\PDOStatement|string|Model
     */
    public function getOneByContacts_id($contacts_id)
    {
        $r =  $this->where('contacts_id', $contacts_id)->where('is_deleted', '0')->find();
        if(!$r){
            return [
                'contacts_id'=>$contacts_id,
                'is_car_check'=>0,
                'is_car_check_push'=>0,
                'car_check_ps'=>'',
                'car_check_select'=>'',
                'is_process_check'=>0,
                'is_process_check_push'=>0,
                'is_product_check'=>0,
                'is_product_check_push'=>0,
                'is_other_check'=>0,
                'is_other_check_push'=>0,
                'is_checkin'=>0,
                'is_mortgage'=>0,
                'process_check_ps'=>'',
                'other_check_ps'=>'',
                'process_check_select'=>'',
                'product_check_ps'=>'',
                'product_check_select'=>'',
                'ps'=>'',
                'is_pass'=>-1,
            ];
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