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

namespace controller;

use service\DataService;
use service\HttpService;
use think\Controller;
use think\Db;
use think\db\Query;

/**
 * 后台权限基础控制器（接口）
 * Class BasicAdmin
 * @package controller
 */
class BasicApi extends Controller
{
    //TODO 获取app_token验证

    /**
     * 页面标题
     * @var string
     */
    public $title;

    /**
     * 根目录
     * @var string
     */
    public $site_url = "http://chexinyuan.com/";

    /**
     * 默认操作数据表
     * @var string
     */
    public $table;

    public $token;

    public $system_user;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
        parent::__construct();
        $controller_name = strtolower($this->request->controller());
        $modele_name = strtolower($this->request->module());
        $action_name = strtolower($this->request->action());
        $get = $this->request->request();
        if ($modele_name == 'api' && $controller_name != 'login' && $controller_name != 'plugs') {
            if($action_name !='settlement'){
                $this->check_token();
            }
        }
    }

    public function check_token()
    {
        $this->token = $this->request->request("app_token");
        if(empty($this->token)){
            $this->error_json(1003,"缺失参数 app_token");
        }
        $this->system_user = Db::name("SystemUser")->where('app_token', $this->token)->field("id")->find();
        empty($this->system_user) && $this->error_json(1002, 'app_token失效，查询不到用户!', []);
    }


    /**
     * 图片地址获得详细链接
     * @param $url
     * @return string
     */
    public function get_full_url($url)
    {
        if (!$url) {
            return '';
        }
        if ($url != '' && strpos($url, "http") === false) {
            return $this->site_url . $url;
        }
        return $url;
    }

    /**
     * 成功返回
     * @param string $msg
     * @param string $data
     */
    protected function success_json($msg = '', $data = '')
    {
        $this->json_return(200, $msg, $data);
    }

    /**
     * 失败返回
     * @param int $code
     * @param string $msg
     * @param string $data
     */
    protected function error_json($code = 400, $msg = '', $data = '')
    {
        $this->json_return($code, $msg, $data);
    }

    protected function json_return($code = 200, $msg = '', $data = '', $token = '')
    {
        header("Content-type:application/json;charset=utf-8");
        $app_token = $this->request->request("app_token") ? $this->request->request("app_token") : '';
        exit(json_encode(['status' => $code, 'message' => $msg, 'data' => $data, 'app_token' => $app_token]));
    }

    /**
     * 表单默认操作
     * @param Query $dbQuery 数据库查询对象
     * @param string $tplFile 显示模板名字
     * @param string $pkField 更新主键规则
     * @param array $where 查询规则
     * @param array $extendData 扩展数据
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    protected function _form($dbQuery = null, $tplFile = '', $pkField = '', $where = [], $extendData = [])
    {
        $db = is_null($dbQuery) ? Db::name($this->table) : (is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery);
        $pk = empty($pkField) ? ($db->getPk() ? $db->getPk() : 'id') : $pkField;
        $pkValue = $this->request->request($pk, isset($where[$pk]) ? $where[$pk] : (isset($extendData[$pk]) ? $extendData[$pk] : null));
        // 非POST请求, 获取数据并显示表单页面
        if (!$this->request->isPost()) {
            $vo = ($pkValue !== null) ? array_merge((array)$db->where($pk, $pkValue)->where($where)->find(), $extendData) : $extendData;
            if (false !== $this->_callback('_form_filter', $vo, [])) {
                empty($this->title) || $this->assign('title', $this->title);
                return $this->fetch($tplFile, ['vo' => $vo]);
            }
            return $vo;
        }
        // POST请求, 数据自动存库
        $data = array_merge($this->request->post(), $extendData);
        if (false !== $this->_callback('_form_filter', $data, [])) {
            $result = DataService::save($db, $data, $pk, $where);
            if (false !== $this->_callback('_form_result', $result, $data)) {
                if ($result !== false) {
                    $this->success('恭喜, 数据保存成功!', '');
                }
                $this->error('数据保存失败, 请稍候再试!');
            }
        }
    }

    /**
     * 列表集成处理方法
     * @param Query $dbQuery 数据库查询对象
     * @param bool $isPage 是启用分页
     * @param bool $isDisplay 是否直接输出显示
     * @param bool $total 总记录数
     * @param array $result 结果集
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    protected function _list($dbQuery = null, $isPage = true, $isDisplay = true, $total = false, $result = [])
    {
        $db = is_null($dbQuery) ? Db::name($this->table) : (is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery);
        // 列表排序默认处理
        if ($this->request->isPost() && $this->request->post('action') === 'resort') {
            foreach ($this->request->post() as $key => $value) {
                if (preg_match('/^_\d{1,}$/', $key) && preg_match('/^\d{1,}$/', $value)) {
                    list($where, $update) = [['id' => trim($key, '_')], ['sort' => $value]];
                    if (false === Db::table($db->getTable())->where($where)->update($update)) {
                        $this->error('列表排序失败, 请稍候再试');
                    }
                }
            }
            $this->success('列表排序成功, 正在刷新列表', '');
        }
        // 列表数据查询与显示
        if (null === $db->getOptions('order')) {
            in_array('sort', $db->getTableFields($db->getTable())) && $db->order('sort asc');
        }
        if ($isPage) {
            $rows = intval($this->request->request('rows', cookie('page-rows')));
            cookie('page-rows', $rows = $rows >= 10 ? $rows : 20);
            // 分页数据处理
            $query = $this->request->request('', '', 'urlencode');
            $page = $db->paginate($rows, $total, ['query' => $query]);
            if (($totalNum = $page->total()) > 0) {
                list($result['total'], $result['list']) = [$totalNum, $page->all()];
            } else {
                list($result['total'], $result['list']) = [$totalNum, $page->all()];
            }
        } else {
            $result['list'] = $db->select();
            $result['total'] = $db->count();
        }
        //TODO 改
        $get_data = $this->request->request();
        $result['keyWord'] = isset($get_data) ? $get_data : (object)[];


        if (false !== $this->_callback('_data_filter', $result['list'], []) && $isDisplay) {
//            !empty($this->title) && $this->assign('title', $this->title);
//            return $this->fetch('', $result);
            return $result;
        }
        return $result;
    }

    /**
     * 当前对象回调成员方法
     * @param string $method
     * @param array|bool $data1
     * @param array|bool $data2
     * @return bool
     */
    protected function _callback($method, &$data1, $data2)
    {
        foreach ([$method, "_" . $this->request->action() . "{$method}"] as $_method) {
            if (method_exists($this, $_method) && false === $this->$_method($data1, $data2)) {
                return false;
            }
        }
        return true;
    }

}
