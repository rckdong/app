<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014-2018 东莞市云拓互联网络科技有限公司
// +----------------------------------------------------------------------
// | 官方网站:http://www.ytclouds.net
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------

namespace app\api\controller;

use controller\BasicApi;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\App;
use app\api\model\UserModel;
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Settlement extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'AppUsers';


    /**
     * 支付方案
     */
    public function programme(){
        $get = $this->request->request();
        $type = isset($get['type'])?$get['type']:1;
        $percent = 0.008;           //开票基数
        $insurance_base = 1.5;      //保险乘积
        $register_cost = 1000;      //上牌成本
        $car_management_cost = 800;  //商品车管理
        $mortgage_cost = 0;             //按揭手续费
        switch ($type){
            case 1:
                //一次性付款方案一

                break;
            case 2:
                //按揭付款方案二
                $percent = 0.01;
                $mortgage_cost = 300;
                break;
            case 3:
                $insurance_base = 1.2;
                //一次性店保付款方案三
                break;
            case 4:
                $percent = 0.01;
                $insurance_base = 1.2;
                $mortgage_cost = 300;
                //按揭店保付款方案四
                break;
            case 5:
                $percent = 0.008;
                $insurance_base = 1.2;
                $register_cost = 500;
                //4s全包方案5
                break;
        }
        $chengben = ($get['A4']*$percent) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8']*$insurance_base) + $register_cost + $get['A10'] + $get['A11'] + $car_management_cost + $mortgage_cost + $get['A14'] + $get['A15'];//当台成本
        $maoli = $get['D3'] - $chengben;//公司毛利
        $lirun = ($get['A4']*$percent) + ($get['A8'] * $insurance_base * 0.5) + ($register_cost - 500) + ($get['A10'] - ($get['A10'] / 1.2)) + $car_management_cost;       //公司利润
        $xslirun = $get['D3'] - $chengben;//销售利润
        $xsjlirun = $xslirun - ($xslirun * 0.3) * $get['A16'] / 100;//销售净利润
        $gjlirun = $lirun + $xslirun - $xsjlirun;   //公司净利润
        echo $maoli;
        echo "<br/>";
        echo $lirun;
        echo "<br/>";
        echo $xslirun;
        echo "<br/>";
        echo $xsjlirun;
        echo "<br/>";
        echo $gjlirun;
    }


    public function test()
    {
        $get = $this->request->request();

        $chengben = ($get['A4']*0.008) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8']*1.5) + 500 + $get['A10'] + $get['A11'] + 800 + $get['A13'] + $get['A14'] + $get['A15'];//当台成本
        $maoli = $get['D3'] - $chengben;//公司毛利
        $lirun = ($get['A4']*0.008) + ($get['A8'] * 1.5 * 0.5) + (1000 - 500) + ($get['A10'] - ($get['A10'] / 1.2)) + 800;       //公司利润
        $xslirun = $get['D3'] - $chengben;//销售利润
        $xsjlirun = $xslirun - ($xslirun * 0.3) * $get['A16'] / 100;//销售净利润
        $gjlirun = $lirun + $xslirun - $xsjlirun;   //公司净利润

        echo "一次性付款方案一 <br/>";
        echo "总收入 ：" . $get['D3'];
        echo "<br/>";
        echo "开票价 ：" . $get['A4']."*0.01";
        echo "<br/>";
        echo "车辆采购成本 ：" . $get['A5'];
        echo "<br/>";
        echo "运输成本 ：" . $get['A6'];
        echo "<br/>";
        echo "购置税成本 ：" . $get['A7'];
        echo "<br/>";
        echo "保险成本 ：" . $get['A8'].'*1.5' ;
        echo "<br/>";
        echo "上牌成本 ：500";
        echo "<br/>";
        echo "精品成本 ：" . $get['A10'] ;
        echo "<br/>";
        echo "刷卡成本 ：" . $get['A11'];
        echo "<br/>";
        echo "商品车管理成本 ：800";
        echo "<br/>";
        echo "金融按揭成本 ：" . $get['A13'];
        echo "<br/>";
        echo "垫资成本 ：" . $get['A14'];
        echo "<br/>";
        echo "其他成本 ：" . $get['A15'];
        echo "<br/>";
        echo "客户满意度100分 ：" . $get['A16'];
        echo "<br/>";

        echo "------------------------------------";
        echo "<br/>";
        echo "单台车成本 : ".$chengben."<br/>公司毛利 : ".$maoli."<br/>公司利润 : ".$lirun."<br/>销售利润 : ".$xslirun."<br/>销售净利润 : ".$xsjlirun."<br/>公司净利润 : ".$gjlirun;
        echo "<br/>";
        echo "------------------------------------";
//        echo "<br/>";
//        echo "单台车成本 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") + 车辆采购成本(" . $get['A5'] . ") + 运输成本(" . $get['A6'] . ") + 购置税成本(" . $get['A7'] . ") + 保险成本(" . $get['A8'] . ") + 500 + 精品成本(" . $get['A10'] . ") + 刷卡成本(" . $get['A11'] . ") + 100 + 金融按揭(" . $get['A13'] . ") + 垫资成本(" . $get['A14'] . ") + 其他成本(" . $get['A15'] . ") =  ";
//        print_r($chengben);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司毛利 ：";
//        echo "<br/>";
//        echo "总收入(" . $get['D3'] . ")- 单台成本 (" . $chengben . ") = ";
//        print_r($maoli);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司利润 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") * 0.8% +(实收保险项目[" . $get['C8'] . "] * 0.5)+（上牌[1000] - 500）+[精品费用[" . $get['C10'] . "]-(精品费用[" . $get['C10'] . "]/1.2)]+商品车管理[800] = ";
//        print_r($lirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售利润 ：";
////        echo "<br/>";
//        echo "总收入[" . $get['D3'] . "]-公司利润[" . $lirun . "] = ";
//        print_r($xslirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售净利润 ：";
////        echo "<br/>";
//        echo "销售利润[" . $xslirun . "]-(销售利润[" . $xslirun . "] * 0.3) * 客户满意度[".$get['A16']."%] = ";
//        print_r($xsjlirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司净利润 ：";
////        echo "<br/>";
//        echo "公司利润[" . $lirun . "]+ 销售利润[" . $xslirun . "]-销售静利润[" . $xsjlirun . "] = ";
//        print_r($gjlirun);
//        echo "<br/>";

    }

    public function test2()
    {
        $get = $this->request->request();

        $chengben = ($get['A4']*0.01) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8']*1.5) + 500 + $get['A10'] + $get['A11'] + 800 + $get['A13'] + $get['A14'] + $get['A15'];//当台成本
        $maoli = $get['D3'] - $chengben;//公司毛利
        $lirun = ($get['A4']*0.01) + ($get['A8'] * 1.5 * 0.5) + (1000 - 500) + ($get['A10'] - ($get['A10'] / 1.2)) + 800;       //公司利润
        $xslirun = $get['D3'] - $chengben;//销售利润
        $xsjlirun = $xslirun - ($xslirun * 0.3) * $get['A16'] / 100;//销售净利润
        $gjlirun = $lirun + $xslirun - $xsjlirun;   //公司净利润

        echo "按揭付款方案二 <br/>";
        echo "总收入 ：" . $get['D3'];
        echo "<br/>";
        echo "开票价 ：" . $get['A4']."*0.01";
        echo "<br/>";
        echo "车辆采购成本 ：" . $get['A5'];
        echo "<br/>";
        echo "运输成本 ：" . $get['A6'];
        echo "<br/>";
        echo "购置税成本 ：" . $get['A7'];
        echo "<br/>";
        echo "保险成本 ：" . $get['A8'].'*1.5' ;
        echo "<br/>";
        echo "上牌成本 ：500";
        echo "<br/>";
        echo "精品成本 ：" . $get['A10'] ;
        echo "<br/>";
        echo "刷卡成本 ：" . $get['A11'];
        echo "<br/>";
        echo "商品车管理成本 ：800";
        echo "<br/>";
        echo "金融按揭成本 ：" . $get['A13'];
        echo "<br/>";
        echo "垫资成本 ：" . $get['A14'];
        echo "<br/>";
        echo "其他成本 ：" . $get['A15'];
        echo "<br/>";
        echo "客户满意度100分 ：" . $get['A16'];
        echo "<br/>";

        echo "------------------------------------";
        echo "<br/>";
        echo "单台车成本 : ".$chengben."<br/>公司毛利 : ".$maoli."<br/>公司利润 : ".$lirun."<br/>销售利润 : ".$xslirun."<br/>销售净利润 : ".$xsjlirun."<br/>公司净利润 : ".$gjlirun;
        echo "<br/>";
        echo "------------------------------------";
//        echo "<br/>";
//        echo "单台车成本 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") + 车辆采购成本(" . $get['A5'] . ") + 运输成本(" . $get['A6'] . ") + 购置税成本(" . $get['A7'] . ") + 保险成本(" . $get['A8'] . ") + 500 + 精品成本(" . $get['A10'] . ") + 刷卡成本(" . $get['A11'] . ") + 100 + 金融按揭(" . $get['A13'] . ") + 垫资成本(" . $get['A14'] . ") + 其他成本(" . $get['A15'] . ") =  ";
//        print_r($chengben);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司毛利 ：";
//        echo "<br/>";
//        echo "总收入(" . $get['D3'] . ")- 单台成本 (" . $chengben . ") = ";
//        print_r($maoli);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司利润 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") * 0.8% +(实收保险项目[" . $get['C8'] . "] * 0.5)+（上牌[1000] - 500）+[精品费用[" . $get['C10'] . "]-(精品费用[" . $get['C10'] . "]/1.2)]+商品车管理[800] = ";
//        print_r($lirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售利润 ：";
////        echo "<br/>";
//        echo "总收入[" . $get['D3'] . "]-公司利润[" . $lirun . "] = ";
//        print_r($xslirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售净利润 ：";
////        echo "<br/>";
//        echo "销售利润[" . $xslirun . "]-(销售利润[" . $xslirun . "] * 0.3) * 客户满意度[".$get['A16']."%] = ";
//        print_r($xsjlirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司净利润 ：";
////        echo "<br/>";
//        echo "公司利润[" . $lirun . "]+ 销售利润[" . $xslirun . "]-销售静利润[" . $xsjlirun . "] = ";
//        print_r($gjlirun);
//        echo "<br/>";

    }

    public function test3()
    {
        $get = $this->request->request();

        $chengben = ($get['A4']*0.008) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8']*1.2) + 500 + $get['A10'] + $get['A11'] + 800 + $get['A13'] + $get['A14'] + $get['A15'];//当台成本
        $maoli = $get['D3'] - $chengben;//公司毛利
        $lirun = ($get['A4']*0.008) + ($get['A8'] * 1.2 * 0.5) + (1000 - 500) + ($get['A10'] - ($get['A10'] / 1.2)) + 800;       //公司利润
        $xslirun = $get['D3'] - $chengben;//销售利润
        $xsjlirun = $xslirun - ($xslirun * 0.3) * $get['A16'] / 100;//销售净利润
        $gjlirun = $lirun + $xslirun - $xsjlirun;   //公司净利润

        echo "一次性店保付款方案三 <br/>";
        echo "总收入 ：" . $get['D3'];
        echo "<br/>";
        echo "开票价 ：" . $get['A4']."*0.008";
        echo "<br/>";
        echo "车辆采购成本 ：" . $get['A5'];
        echo "<br/>";
        echo "运输成本 ：" . $get['A6'];
        echo "<br/>";
        echo "购置税成本 ：" . $get['A7'];
        echo "<br/>";
        echo "保险成本 ：" . $get['A8'].'*1.2' ;
        echo "<br/>";
        echo "上牌成本 ：500";
        echo "<br/>";
        echo "精品成本 ：" . $get['A10'] ;
        echo "<br/>";
        echo "刷卡成本 ：" . $get['A11'];
        echo "<br/>";
        echo "商品车管理成本 ：800";
        echo "<br/>";
        echo "金融按揭成本 ：" . $get['A13'];
        echo "<br/>";
        echo "垫资成本 ：" . $get['A14'];
        echo "<br/>";
        echo "其他成本 ：" . $get['A15'];
        echo "<br/>";
        echo "客户满意度100分 ：" . $get['A16'];
        echo "<br/>";

        echo "------------------------------------";
        echo "<br/>";
        echo "单台车成本 : ".$chengben."<br/>公司毛利 : ".$maoli."<br/>公司利润 : ".$lirun."<br/>销售利润 : ".$xslirun."<br/>销售净利润 : ".$xsjlirun."<br/>公司净利润 : ".$gjlirun;
        echo "<br/>";
        echo "------------------------------------";
//        echo "<br/>";
//        echo "单台车成本 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") + 车辆采购成本(" . $get['A5'] . ") + 运输成本(" . $get['A6'] . ") + 购置税成本(" . $get['A7'] . ") + 保险成本(" . $get['A8'] . ") + 500 + 精品成本(" . $get['A10'] . ") + 刷卡成本(" . $get['A11'] . ") + 100 + 金融按揭(" . $get['A13'] . ") + 垫资成本(" . $get['A14'] . ") + 其他成本(" . $get['A15'] . ") =  ";
//        print_r($chengben);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司毛利 ：";
//        echo "<br/>";
//        echo "总收入(" . $get['D3'] . ")- 单台成本 (" . $chengben . ") = ";
//        print_r($maoli);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司利润 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") * 0.8% +(实收保险项目[" . $get['C8'] . "] * 0.5)+（上牌[1000] - 500）+[精品费用[" . $get['C10'] . "]-(精品费用[" . $get['C10'] . "]/1.2)]+商品车管理[800] = ";
//        print_r($lirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售利润 ：";
////        echo "<br/>";
//        echo "总收入[" . $get['D3'] . "]-公司利润[" . $lirun . "] = ";
//        print_r($xslirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售净利润 ：";
////        echo "<br/>";
//        echo "销售利润[" . $xslirun . "]-(销售利润[" . $xslirun . "] * 0.3) * 客户满意度[".$get['A16']."%] = ";
//        print_r($xsjlirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司净利润 ：";
////        echo "<br/>";
//        echo "公司利润[" . $lirun . "]+ 销售利润[" . $xslirun . "]-销售静利润[" . $xsjlirun . "] = ";
//        print_r($gjlirun);
//        echo "<br/>";

    }

    public function test4()
    {
        $get = $this->request->request();

        $chengben = ($get['A4']*0.01) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8']*1.2) + 500 + $get['A10'] + $get['A11'] + 800 + $get['A13'] + $get['A14'] + $get['A15'];//当台成本
        $maoli = $get['D3'] - $chengben;//公司毛利
        $lirun = ($get['A4']*0.01) + ($get['A8'] * 1.2 * 0.5) + (1000 - 500) + ($get['A10'] - ($get['A10'] / 1.2)) + 800;       //公司利润
        $xslirun = $get['D3'] - $chengben;//销售利润
        $xsjlirun = $xslirun - ($xslirun * 0.3) * $get['A16'] / 100;//销售净利润
        $gjlirun = $lirun + $xslirun - $xsjlirun;   //公司净利润

        echo "按揭店保付款方案四 <br/>";
        echo "总收入 ：" . $get['D3'];
        echo "<br/>";
        echo "开票价 ：" . $get['A4']."*0.008";
        echo "<br/>";
        echo "车辆采购成本 ：" . $get['A5'];
        echo "<br/>";
        echo "运输成本 ：" . $get['A6'];
        echo "<br/>";
        echo "购置税成本 ：" . $get['A7'];
        echo "<br/>";
        echo "保险成本 ：" . $get['A8'].'*1.2' ;
        echo "<br/>";
        echo "上牌成本 ：500";
        echo "<br/>";
        echo "精品成本 ：" . $get['A10'] ;
        echo "<br/>";
        echo "刷卡成本 ：" . $get['A11'];
        echo "<br/>";
        echo "商品车管理成本 ：800";
        echo "<br/>";
        echo "金融按揭成本 ：" . $get['A13'];
        echo "<br/>";
        echo "垫资成本 ：" . $get['A14'];
        echo "<br/>";
        echo "其他成本 ：" . $get['A15'];
        echo "<br/>";
        echo "客户满意度100分 ：" . $get['A16'];
        echo "<br/>";

        echo "------------------------------------";
        echo "<br/>";
        echo "单台车成本 : ".$chengben."<br/>公司毛利 : ".$maoli."<br/>公司利润 : ".$lirun."<br/>销售利润 : ".$xslirun."<br/>销售净利润 : ".$xsjlirun."<br/>公司净利润 : ".$gjlirun;
        echo "<br/>";
        echo "------------------------------------";
//        echo "<br/>";
//        echo "单台车成本 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") + 车辆采购成本(" . $get['A5'] . ") + 运输成本(" . $get['A6'] . ") + 购置税成本(" . $get['A7'] . ") + 保险成本(" . $get['A8'] . ") + 500 + 精品成本(" . $get['A10'] . ") + 刷卡成本(" . $get['A11'] . ") + 100 + 金融按揭(" . $get['A13'] . ") + 垫资成本(" . $get['A14'] . ") + 其他成本(" . $get['A15'] . ") =  ";
//        print_r($chengben);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司毛利 ：";
//        echo "<br/>";
//        echo "总收入(" . $get['D3'] . ")- 单台成本 (" . $chengben . ") = ";
//        print_r($maoli);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司利润 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") * 0.8% +(实收保险项目[" . $get['C8'] . "] * 0.5)+（上牌[1000] - 500）+[精品费用[" . $get['C10'] . "]-(精品费用[" . $get['C10'] . "]/1.2)]+商品车管理[800] = ";
//        print_r($lirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售利润 ：";
////        echo "<br/>";
//        echo "总收入[" . $get['D3'] . "]-公司利润[" . $lirun . "] = ";
//        print_r($xslirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售净利润 ：";
////        echo "<br/>";
//        echo "销售利润[" . $xslirun . "]-(销售利润[" . $xslirun . "] * 0.3) * 客户满意度[".$get['A16']."%] = ";
//        print_r($xsjlirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司净利润 ：";
////        echo "<br/>";
//        echo "公司利润[" . $lirun . "]+ 销售利润[" . $xslirun . "]-销售静利润[" . $xsjlirun . "] = ";
//        print_r($gjlirun);
//        echo "<br/>";

    }

    public function test5()
    {
        $get = $this->request->request();

        $chengben = ($get['A4']*0.008) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8']*1.2) + 500 + $get['A10'] + $get['A11'] + 800 + $get['A13'] + $get['A14'] + $get['A15'];//当台成本
        $maoli = $get['D3'] - $chengben;//公司毛利
        $lirun = ($get['A4']*0.008) + ($get['A8'] * 1.2 * 0.5) + (1000 - 500) + ($get['A10'] - ($get['A10'] / 1.2)) + 800;       //公司利润
        $xslirun = $get['D3'] - $chengben;//销售利润
        $xsjlirun = $xslirun - ($xslirun * 0.3) * $get['A16'] / 100;//销售净利润
        $gjlirun = $lirun + $xslirun - $xsjlirun;   //公司净利润

        echo "4S全包方案五 <br/>";
        echo "总收入 ：" . $get['D3'];
        echo "<br/>";
        echo "开票价 ：" . $get['A4']."*0.008";
        echo "<br/>";
        echo "车辆采购成本 ：" . $get['A5'];
        echo "<br/>";
        echo "运输成本 ：" . $get['A6'];
        echo "<br/>";
        echo "购置税成本 ：" . $get['A7'];
        echo "<br/>";
        echo "保险成本 ：" . $get['A8'].'*1.2' ;
        echo "<br/>";
        echo "上牌成本 ：500";
        echo "<br/>";
        echo "精品成本 ：" . $get['A10'] ;
        echo "<br/>";
        echo "刷卡成本 ：" . $get['A11'];
        echo "<br/>";
        echo "商品车管理成本 ：800";
        echo "<br/>";
        echo "金融按揭成本 ：" . $get['A13'];
        echo "<br/>";
        echo "垫资成本 ：" . $get['A14'];
        echo "<br/>";
        echo "其他成本 ：" . $get['A15'];
        echo "<br/>";
        echo "客户满意度100分 ：" . $get['A16'];
        echo "<br/>";

        echo "------------------------------------";
        echo "<br/>";
        echo "单台车成本 : ".$chengben."<br/>公司毛利 : ".$maoli."<br/>公司利润 : ".$lirun."<br/>销售利润 : ".$xslirun."<br/>销售净利润 : ".$xsjlirun."<br/>公司净利润 : ".$gjlirun;
        echo "<br/>";
        echo "------------------------------------";
//        echo "<br/>";
//        echo "单台车成本 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") + 车辆采购成本(" . $get['A5'] . ") + 运输成本(" . $get['A6'] . ") + 购置税成本(" . $get['A7'] . ") + 保险成本(" . $get['A8'] . ") + 500 + 精品成本(" . $get['A10'] . ") + 刷卡成本(" . $get['A11'] . ") + 100 + 金融按揭(" . $get['A13'] . ") + 垫资成本(" . $get['A14'] . ") + 其他成本(" . $get['A15'] . ") =  ";
//        print_r($chengben);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司毛利 ：";
//        echo "<br/>";
//        echo "总收入(" . $get['D3'] . ")- 单台成本 (" . $chengben . ") = ";
//        print_r($maoli);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司利润 ：";
//        echo "<br/>";
//        echo "开票价(" . $get['A4'] . ") * 0.8% +(实收保险项目[" . $get['C8'] . "] * 0.5)+（上牌[1000] - 500）+[精品费用[" . $get['C10'] . "]-(精品费用[" . $get['C10'] . "]/1.2)]+商品车管理[800] = ";
//        print_r($lirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售利润 ：";
////        echo "<br/>";
//        echo "总收入[" . $get['D3'] . "]-公司利润[" . $lirun . "] = ";
//        print_r($xslirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "销售净利润 ：";
////        echo "<br/>";
//        echo "销售利润[" . $xslirun . "]-(销售利润[" . $xslirun . "] * 0.3) * 客户满意度[".$get['A16']."%] = ";
//        print_r($xsjlirun);
//        echo "<br/>";
//        echo "<br/>";
//        echo "公司净利润 ：";
////        echo "<br/>";
//        echo "公司利润[" . $lirun . "]+ 销售利润[" . $xslirun . "]-销售静利润[" . $xsjlirun . "] = ";
//        print_r($gjlirun);
//        echo "<br/>";

    }

}
