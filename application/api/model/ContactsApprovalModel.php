<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class ContactsApprovalModel extends Model
{
    protected $table = 'contacts_approval';


    public function getOne($id)
    {
        return $this->where('id', $id)->where('is_deleted', '0')->find();
    }
}