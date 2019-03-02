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

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\PushService;
use think\Db;

/**
 * 系统用户管理控制器
 * Class User
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 18:12
 */
class Test extends BasicAdmin
{

    public function test()
    {
        $r = PushService::notify(10000, ['status' => 200, 'msg' => '合同编号：' . 12312312 . ' ,用户不同意该合同，需修改', 'data' => '新消息']);
        var_dump($r);
    }

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Test';

    /**
     * 用户列表
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function index()
    {
        $this->title = '测试结算数据';
        list($get, $db) = [$this->request->get(), Db::name($this->table)];

        return parent::_list($db->where(['is_deleted' => '0']));
    }


    /**
     * 用户添加
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function add()
    {
        return $this->_form($this->table, 'form');
    }

    /**
     * 用户编辑
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function edit()
    {
        return $this->_form($this->table, 'form');
    }


    /**
     * 表单数据默认处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _form_filter(&$data)
    {
        if ($this->request->isPost()) {
            $get = $this->request->request();
            $type = isset($get['type']) ? $get['type'] : 1;


            $percent = 0.008;           //开票基数
            $insurance_base = 1.5;      //保险乘积
            $register_cost = 1000;      //上牌成本
            $car_management_cost = 800;  //商品车管理
            $mortgage_cost = 0;             //按揭手续费
            switch ($type) {
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
                    $car_management_cost = 0;
                    //4s全包方案5
                    break;
                case 6:
                    //按揭付款方案二
                    $percent = 0.02;
                    $get['A8_1'] = 0;
                    $get['A8_2'] = 0;
                    $get['A8_3'] = 0;
                    $get['A8_4'] = 0;
                    $data['A8_1'] = 0;
                    $data['A8_2'] = 0;
                    $data['A8_3'] = 0;
                    $data['A8_4'] = 0;
                    break;
            }
            $data['type'] = $type;
            //单台成本
//            if($type == 5){
//                $data['chengben'] = $get['A5'] + $get['A6'] + $get['A7'] + $get['A8_1'] + $get['A8_2'] + $get['A8_3'] + $get['A8_4'] + 500 + $get['A10'] + $get['A11'] + $get['A14'] + $mortgage_cost + $get['A15'];
//            }else{
//
//            }
            $data['chengben'] = $get['A5'] + $get['A6'] + $get['A7'] + $get['A8_1'] + $get['A8_2'] + $get['A8_3'] + $get['A8_4'] + 500 + $get['A10'] + $get['A11'] + $get['A14'] + 100 + $mortgage_cost + $get['A15'];

            //公司毛利润
            $data['maoli'] = ($get['A4'] * $percent) + $get['A5'] + $get['A6'] + $get['A7'] + ($get['A8_1'] * $insurance_base) + $get['A8_2'] + $get['A8_3'] + $get['A8_4'] + $get['A9'] + $get['A10'] + $get['A11'] + $get['A14'] + $car_management_cost + $mortgage_cost + $get['A15'];

            //销售毛利润
            $data['xslirun'] = $get['D3'] - $data['maoli'];

            //销售毛利润判断(当销售毛利润 小于 (总收入-公司毛利润)/2 )
            $r = ($get['D3'] - $data['chengben']) / 2;
            if ($data['xslirun'] < $r) {
                $data['xslirun'] = $r;
            }

            //公司应收总成本
            $data['company_chengben'] = $data['maoli'] - ($data['xslirun'] * 0.3) * (100 - $get['A16']) * 0.01;


            //客户满意度扣分
            $data['percent_discount'] = $data['maoli'] - $data['maoli'] - ($data['xslirun'] * 0.3) * (100 - $get['A16']) * 0.01;

            //销售净利润
            $data['xsjlirun'] = $data['xslirun'] - abs($data['percent_discount']);

            //公司净利润
            $data['gjlirun'] = $get['D3'] - $data['chengben'] - $data['xsjlirun'];
        }
    }


    public function print_excel()
    {
        $get = $this->request->get();
        $id = $get['id'];
        $info = Db::name("Test")->where("id", $id)->find();


        $percent = 0.008;           //开票基数
        $insurance_base = 1.5;      //保险乘积
        $register_cost = 1000;      //上牌成本
        $car_management_cost = 800;  //商品车管理
        $mortgage_cost = 0;             //按揭手续费
        switch ($info['type']) {
            case 1:
                //一次性付款方案一
                $name = "一次性付款方案一";
                break;
            case 2:
                //按揭付款方案二
                $name = "按揭付款方案二";
                $percent = 0.01;
                $mortgage_cost = 300;
                break;
            case 3:
                //一次性店保付款方案三
                $name = "一次性店保付款方案三";
                $insurance_base = 1.2;
                break;
            case 4:
                //按揭店保付款方案四
                $name = "按揭店保付款方案四";
                $percent = 0.01;
                $insurance_base = 1.2;
                $mortgage_cost = 300;
                break;
            case 5:
                //4s全包方案5
                $percent = 0.008;
                $insurance_base = 1.2;
                $register_cost = 500;
                $name = "4s全包方案5";
                break;
            case 6:
                //无保险二
                $percent = 0.02;
                $name = "无保险";
                break;
        }

        $all_in = ($info["A8_1"] * $insurance_base) + $info['A8_2'] + $info['A8_3'] + $info['A8_4'];

        $strTable = '<table width="1100" border="1">';
        $strTable .= '<tr><td colspan="12" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">销售经纪人结算表(测试)</td></tr>';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >销售顾问</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">董兵兵</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">客户名</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">张群中</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">车架号</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">合同号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">店面</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">龙岗店</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $name . '</td>';
        $strTable .= '<td colspan="3" style="text-align:center;font-size:12px;" width="*" >口 0.6%   口0.8%    口1%</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">车型</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*">宝马530</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">供应商</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">交车日期</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr></tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >项目</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >金额</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >备注</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">支出明细</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >开票价（0.6-1）%</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . ($info["A4"] * $percent) . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A4"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >车辆采购成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A5"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实际付出车款	</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >运输成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A6"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >大板车+小板车</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >购置税成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A7"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实缴</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td rowspan="2" style="text-align:center;font-size:12px;" width="*" >保险成本</td>';
        $strTable .= '<td rowspan="2" style="text-align:center;font-size:12px;" width="*" >' . $all_in . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >商业险</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >交强/车船</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >质保 85折</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >店保+12%</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["A8_1"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["A8_2"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["A8_3"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["A8_4"] . '</td>';
        $strTable .= '</tr>';


        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >上牌成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A9"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >中规固定</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >精品成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A10"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >表格为准</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >刷卡成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A11"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实际为准</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >商品车管理</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $car_management_cost . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >固定</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >金融按揭</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $mortgage_cost . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >放款1笔300+</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >垫资成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A14"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实际发生</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >其他成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["A15"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" ></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';


        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >单台成本</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["chengben"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >公司毛利润</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["maoli"] . '</td>';
        $strTable .= '</tr>';


        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >客户满意度</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["A16"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >公司应收总成本</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["company_chengben"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >公司净利润</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["gjlirun"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td  colspan="2" style="text-align:center;font-size:12px;" width="*" ></td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >销售毛利润</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >销售净利润</td>';
        $strTable .= '<td  colspan="2"  style="text-align:center;font-size:12px;" width="*" >销售经纪人佣金80%</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >上级奖励</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >总经理奖励</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td  colspan="2" style="text-align:center;font-size:12px;" width="*" >总计实收</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td  colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["D3"] . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["xslirun"] . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["xsjlirun"] . '</td>';
        $strTable .= '<td  colspan="2"  style="text-align:center;font-size:12px;" width="*" >' . $info["xsjlirun"] * 0.8 . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["xsjlirun"] * 0.1 . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["xsjlirun"] * 0.1 . '</td>';
        $strTable .= '</tr>';

        $strTable .= '</table>';
        downloadExcel($strTable, "测试号_" . $info["id"]);
        exit();

    }

    /**
     * 删除用户
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        if (in_array('10000', explode(',', $this->request->post('id')))) {
            $this->error('系统超级账号禁止删除！');
        }
        if (DataService::update($this->table)) {
            $this->success("用户删除成功！", '');
        }
        $this->error("用户删除失败，请稍候再试！");
    }

    /**
     * 用户禁用
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbid()
    {
        if (in_array('10000', explode(',', $this->request->post('id')))) {
            $this->error('系统超级账号禁止操作！');
        }
        if (DataService::update($this->table)) {
            $this->success("用户禁用成功！", '');
        }
        $this->error("用户禁用失败，请稍候再试！");
    }

    /**
     * 用户禁用
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function resume()
    {
        if (DataService::update($this->table)) {
            $this->success("用户启用成功！", '');
        }
        $this->error("用户启用失败，请稍候再试！");
    }

}
