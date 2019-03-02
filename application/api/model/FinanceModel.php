<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class FinanceModel extends Model
{
    protected $table = 'Finance';

    //收款选项
    protected $income_type = ['购车款','购车款(订金)', '购置税款', '保险款', '精品款', '上牌款', '按揭款', '银行放款'];

    protected $ex_income_type = ["返利","金融","保险","车辆","其他"];

    //支付类型
    protected $pay_type = ['刷卡', '现金', '微信', '支付宝','转账'];

    //支出选项
    protected $out_type = ["购车款","购置税款","商业保险","交强险/车船税","店保","质保","精品款","上牌款","运输费","退款","垫资款",'其他成本'];


    public function get_out_type(){
        return $this->out_type;
    }

    public function get_ex_income_type(){
        return $this->ex_income_type;
    }

    public function get_incom_type(){
        return $this->income_type;
    }

    public function get_pay_type(){
        return $this->pay_type;
    }

    public function get_out_type_id($out_type)
    {
        switch ($out_type) {
            case "购车款":
                return 0;
                break;
            case "购置税款":
                return 1;
                break;
            case "商业保险":
                return 2;
                break;
            case "交强险/车船税":
                return 3;
                break;
            case "店保":
                return 4;
                break;
            case "质保":
                return 5;
                break;
            case "精品款":
                return 6;
                break;
            case "上牌款":
                return 7;
                break;
            case "运输费":
                return 8;
                break;
            case "退款":
                return 9;
                break;
            case "垫资款":
                return 10;
                break;
            case "其他成本":
                return 11;
                break;
        }
        return false;
    }

    public function get_income_id($income_type)
    {
        switch ($income_type) {
            case "购车款":
                return 0;
                break;
            case "购车款(订金)":
                return 1;
                break;
            case "购置税款":
                return 2;
                break;
            case "保险款":
                return 3;
                break;
            case "精品款":
                return 4;
                break;
            case "上牌款":
                return 5;
                break;
            case "按揭款":
                return 6;
                break;
            case "银行放款":
                return 7;
                break;
        }
        return false;
    }

    public function get_ex_income_id($ex_income_type)
    {
        switch ($ex_income_type) {
            case "返利":
                return 0;
                break;
            case "金融":
                return 1;
                break;
            case "保险":
                return 2;
                break;
            case "车辆":
                return 3;
                break;
            case "其他":
                return 4;
                break;
        }
        return false;
    }

    public function get_pay_type_id($pay_type)
    {
        switch ($pay_type) {
            case "刷卡":
                return 0;
                break;
            case "现金":
                return 1;
                break;
            case "微信":
                return 2;
                break;
            case "支付宝":
                return 3;
                break;
            case "转账":
                return 4;
                break;
        }
        return false;
    }

}