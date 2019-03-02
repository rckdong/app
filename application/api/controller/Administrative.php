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

use app\api\model\AdministrativeModel;
use app\api\model\DepartmentModel;
use app\api\model\FinanceModel;
use app\api\model\SystemUserModel;
use app\api\service\AdministrativeService;
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
class Administrative extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Administrative';


    /**
     * 收支列表
     */
    public function index()
    {
        $get = $this->request->request();
        $db = Db::name($this->table)->order('id desc');
        if (isset($get['type']) && $get['type'] !== '') {
            $db->where('type', $get['type']);
        }
        $db->where('is_deleted', 0);
        $result = parent::_list($db);

        if(isset($get['export']) && $get['export'] ==1){
            $administrativeService = new AdministrativeService();
            $administrativeService->export_excel($result['list']);
            exit();
        }

        $result['incomes'] = Db::name($this->table)->where("type",0)->where("is_deleted",0)->sum("money");
        $result['expenses'] =  Db::name($this->table)->where("type",1)->where("is_deleted",0)->sum("money");
        $this->success_json('获取数据成功', $result);
    }

    public function _index_data_filter(&$list)
    {
        $DepartmentModel = new DepartmentModel();
        $all_department = $DepartmentModel->getAll();
        $AdministrativeModel = new AdministrativeModel();
        $income_type = $AdministrativeModel->get_income_type();
        $out_type = $AdministrativeModel->get_out_type();
        //获取列表中的人员
        $saler_ids = array_column($list, 'admin_id');
        $saler_ids = array_unique($saler_ids);
        $saler_ids = implode(',', $saler_ids);
        $systemUserModel = new SystemUserModel();
        if ($saler_ids) {
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
        }
        foreach ($list as $key => $val) {
            $list[$key]['certificate'] = $this->get_full_url($val['certificate']);
            $list[$key]['department'] = $all_department[$val['department_id']]['name'];
            if($all_department[$val['department_id']]['is_deleted'] == 1){
                $list[$key]['department'] =  $list[$key]['department'].'【已删】';
            }
            $list[$key]['clerk_name'] = isset($saler_names[$val['admin_id']]) ? $saler_names[$val['admin_id']] : '';
            if ($val['type'] == 1) {
                $list[$key]['option'] = $out_type[$val['option']];
            } else {
                $list[$key]['option'] = $income_type[$val['option']];
            }
        }
    }

    /**
     * 获取收款的选项
     */
    public function get_options()
    {
        $FinanceModel = new AdministrativeModel();
        $DepartmentModel = new DepartmentModel();
        $res['department'] = $DepartmentModel->get_all_two();
        $res['income_type'] = $FinanceModel->get_income_type();
        $res['out_type'] = $FinanceModel->get_out_type();
        //TODO 资产类型
        $res['asset_type'] = ["房租押金", "类型2", "类型3"];
        $this->success_json('获取数据成功', $res);
    }


    /**
     * 添加营业收入记录
     */
    public function add_log()
    {
        $get = $this->request->request();
        $vi_arr = [ 'option', 'ps', 'certificate', 'type', 'money', 'department_id', 'is_invoice'];
        foreach ($vi_arr as $key => $val) {
            if (!isset($get[$val]) || $get[$val] === '') {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        //TODO 添加处理人ID,增加收入或者支出的业务
        $savedata['admin_id'] = $this->system_user['id'];
        $FinanceModel = new AdministrativeModel();
        $savedata['type'] = ($get['type'] == 1) ? $get['type'] : 0;
        $savedata['asset_type'] = isset($get['asset_type']) ? $get['asset_type'] : '';
        $savedata['is_invoice'] = ($get['is_invoice'] == 1) ? $get['is_invoice'] : 0;
        if ($get['type'] == 1) {
            $savedata['option'] = $FinanceModel->get_out_type_id($get['option']);
        } else {
            $savedata['option'] = $FinanceModel->get_income_id($get['option']);
        }

        if ($savedata['option'] === false) {
            $this->error_json(1001,  '参数验证错误', []);
        }
        $savedata['department_id'] = $get['department_id'];
        $savedata['money'] = $get['money'];
        $savedata['ps'] = $get['ps'];
        $savedata['certificate'] = $get['certificate'];
        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('保存成功', []);
    }


}
