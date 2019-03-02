<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27
 * Time: 18:34
 */
namespace app\api\model;

use think\Model;

class AdministrativeModel extends Model
{
    protected $table = 'Administrative';

    //收款选项
    protected $income_type = ['投资款', '借款', '其他'];


    //支出选项
    protected $out_type = [
        '行政费用',
        '水电费',
        '管理费',
        '房租',
        '差旅',
        '会议',
        '公关',
        '建设',
        '交通',
        '快递',
        '杂费',
        '办公用品',
        '固定财产',
        '分红',
        '财务费用',
        '借款',
        '预付',
        '佣金',
        '其他',
    ];


    public function get_income_type(){
        return $this->income_type;
    }

    public function get_out_type(){
        return $this->out_type;
    }

    public function get_income_id($income_type)
    {
        switch ($income_type) {
            case "投资款":
                return 0;
                break;
            case "借款":
                return 1;
                break;
            case "其他":
                return 2;
                break;
        }
        return false;
    }

    public function get_out_type_id($out_type)
    {
        switch ($out_type) {
            case "行政费用":
                return 0;
                break;
            case "水电费":
                return 1;
                break;
            case "管理费":
                return 2;
                break;
            case "房租":
                return 3;
                break;
            case "差旅":
                return 4;
                break;
            case "会议":
                return 5;
                break;
            case "公关":
                return 6;
                break;
            case "建设":
                return 7;
                break;
            case "交通":
                return 8;
                break;
            case "快递":
                return 9;
                break;
            case "杂费":
                return 10;
                break;
            case "办公用品":
                return 11;
                break;
            case "固定财产":
                return 12;
                break;
            case "分红":
                return 13;
                break;
            case "财务费用":
                return 14;
                break;
            case "借款":
                return 15;
                break;
            case "预付":
                return 16;
                break;
            case "佣金":
                return 17;
                break;
            case "其他":
                return 18;
                break;
        }
        return false;
    }



}