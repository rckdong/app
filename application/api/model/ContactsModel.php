<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class ContactsModel extends Model
{
    protected $table = 'contacts';

    //退订描述
    protected $cancel_desc = [
        '战败原因',
        '按揭不通过',
        '价格过高',
        '服务不好',
        '其他原因',
    ];

    /**
     * 根据id获取用户信息
     * @param $id
     * @param string $fields
     * @return array|null|\PDOStatement|string|Model
     */
    public function getOne($id, $fields = "*")
    {
        return $this->where('id', $id)->field($fields)->where('is_deleted', '0')->find();
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


    /**
     * 获取车辆检查
     * @param string $ids
     * @return array
     */
    public function get_car_check($ids = '', $car_check)
    {
        $ids = explode(',', $ids);
        if ($ids) {
            foreach ($car_check as $key => &$value) {
                if (in_array($value['key'], $ids)) {
                    $value['is_check'] = true;
                }
            }
        }
        return $car_check;
    }

    /**
     * 获取手续检查
     * @param string $ids
     * @return array
     */
    public function get_process_check($ids = '', $process_check)
    {
        $ids = explode(',', $ids);
        if ($ids) {
            foreach ($process_check as $key => &$value) {
                if (in_array($value['key'], $ids)) {
                    $value['is_check'] = true;
                }
            }
        }
        return $process_check;
    }


    /**
     * 精品选择
     * @param string $ids
     * @param $need_products
     * @return array
     */
    public function get_product_check($ids = '', $need_products)
    {
        $check_arr = [];
        $ids = explode(',', $ids);
        $i = 0;
        foreach ($need_products as $key => $val) {
            $check_arr[$i]['key'] = $val['id'];
            $check_arr[$i]['value'] = $val['name'];
            if (count($ids) > 0) {
                if (in_array($val['name'], $ids)) {
                    $check_arr[$i]['is_check'] = true;
                } else {
                    $check_arr[$i]['is_check'] = false;
                }
            } else {
                $check_arr[$i]['is_check'] = true;
            }
            $i++;
        }
        return $check_arr;
    }

}