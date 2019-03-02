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

use app\api\service\SettlementService;
use controller\BasicApi;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use service\FileService;
use think\App;
use think\Db;
use think\Exception;
use think\Image;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Plugs extends BasicApi
{

    /**
     * 通用文件上传
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @throws \OSS\Core\OssException
     */
    public function upload()
    {
        $file = $this->request->file('file');
        if (!$file->checkExt(strtolower(sysconf('storage_local_exts')))) {
            $this->error_json(400, '文件上传类型受限', []);
        }
        $ext = strtolower(pathinfo($file->getInfo('name'), 4));
        $save_path = $this->request->post("save_path") ? strtolower($this->request->post("save_path")) : 'unknow';
        $names = time() . rand(10000, 99999);
        $filename = "{$save_path}/{$names}.{$ext}";
        // 文件上传Token验证
//        if ($this->request->post('token') !== md5($filename . session_id())) {
//            $this->error_json(400, '文件上传验证失败', []);
//        }
        // 文件上传处理
        if (($info = $file->move("static/upload/{$save_path}", "{$names}.{$ext}", true))) {
            if (($site_url = FileService::getFileUrl($filename, 'local'))) {
                $data['site_url'] = $site_url;
                $return_path = explode('static', $site_url);
                $data['img_url'] = '/static' . $return_path[1];
                $this->success_json("文件上传成功", $data);
            }
        }
        $this->error_json(400, '文件上传失败', []);
    }


    /**
     * 本地文件删除
     */
    public function del_file()
    {
        $img_url = $this->request->post("img_url");
        $img_url1 = substr($img_url,0,1);
        if($img_url1 == '/'){
            $img_url = substr($img_url,1);
        }
        if (!$img_url) {
            $this->error_json(1003, '参数不全', []);
        }
        if(!file_exists($img_url))
        {
            $this->error_json(400, "文件{$img_url}，不存在", []);
        }
//        fopen($img_url, 'a+');
        if (!unlink($img_url)) {
            $this->error_json(400, "文件{$img_url}删除失败", []);
        } else {
            $this->success_json("文件{$img_url}删除成功", []);
        }
    }

//    public function test2(){
//        $get['contact_id'] = 49;
//        $get['star'] = 10;
//        $settlementService = new SettlementService();
//        $r = $settlementService->add_log($get['contact_id'],$get['star']);
//        print_r($r);
//    }
//
//    /**
//     * test
//     */
//
    function test()
    {
        include_once "./extend/PHPExcel/PHPExcel.php";
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        $filename = "./b.xlsx";
        if( ! $PHPReader->canRead($filename))
        {
            //使用2007读取不了，就用2003读取。
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if( ! $PHPReader->canRead($filename)){
                echo 'not Excel';
                return ;
            }
        }
        $PHPExcel = $PHPReader->load($filename); //读取文件

        $currentSheet = $PHPExcel->getSheet(0); //读取第一个工作簿
        $allColumn = $currentSheet->getHighestColumn(); // 所有列数，得出的是字母：F
        $allColumn_num = \PHPExcel_Cell::columnIndexFromString($allColumn);//F转换为6
        $allRow =  $currentSheet->getHighestRow(); // 所有行数
        $data = [];

        //遍历行，遍历列，逐个读取数据：默认有标题，所以从第二行读起

        for ($rowIndex = 2; $rowIndex <= $allRow; $rowIndex++)
        {
            $sub=[];
            for ($colIndex=0; $colIndex < $allColumn_num; $colIndex++) {
                //stringFromColumnIndex($colIndex); 将字母转换为数字
                $sub[]=$currentSheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colIndex).$rowIndex)->getValue();
            }
            $data[]=$sub;
        }
        $allColumn=$allColumn_num;
//        return ['data'=>$data,'col'=>$allColumn,'row'=>$allRow];


        foreach ($data as $k=>$v){
            if($v[2] == ''){
                continue;
            }
            $pid = 0;
            switch ($v[0]){
                case "奔驰":
                    $pid = 77;
                    break;
                case "宝马":
                    $pid = 111;
                    break;
                case "保时捷":
                    $pid = 143;
                    break;
                case "路虎":
                    $pid = 151;
                    break;
                case "奥迪":
                    $pid = 160;
                    break;
                case "平行进口":
                    $pid = 193;
                    break;
            }

            $good_cate = Db::name("GoodsCate")->where("cate_title",$v[1])->where("pid",$pid)->find();
            $goods_info = Db::name("Goods")->where("goods_title",$v[2])->where("cate_id",$good_cate['id'])->find();
//            echo "<pre>";
//            print_r($goods_info);
//            echo "</pre>";
            $savedata['id'] = $goods_info['id'];
            $savedata['guid_price'] = $v[3];
            $savedata['goods_prices'] = $v[4];
            DataService::save("Goods",$savedata);
        }
        echo 12;
        exit;
    }
//
//    public function insert_test(){
//        $db = Db::name("Goods");
//        $res = $db->where("is_deleted",0)->select();
//        foreach ($res as $k =>$v){
//            $savedata = [];
//            $savedata['id'] = $v['id'];
//            $savedata['goods_logo'] = 'http://chexinyuan.com/static/upload/car/'.$v['cate_id'].'/1.jpg';
//            $savedata['goods_content'] = "&lt;p&gt;&lt;img border=&quot;0&quot; src=&quot;http://chexinyuan.com/static/upload/car/".$v['cate_id']."/1.jpg&quot; style=&quot;max-width:500px&quot; title=&quot;image&quot; /&gt;&lt;img border=&quot;0&quot; src=&quot;http://chexinyuan.com/static/upload/car/".$v['cate_id']."/2.jpg&quot; style=&quot;max-width:500px&quot; title=&quot;image&quot; /&gt;&lt;img border=&quot;0&quot; src=&quot;http://chexinyuan.com/static/upload/car/".$v['cate_id']."/3.jpg&quot; style=&quot;max-width:500px&quot; title=&quot;image&quot; /&gt;&lt;img border=&quot;0&quot; src=&quot;http://chexinyuan.com/static/upload/car/".$v['cate_id']."/4.jpg&quot; style=&quot;max-width:500px&quot; title=&quot;image&quot; /&gt;&lt;/p&gt;";
////            http://car.com/static/upload/4e9c449e6dfd12e7/44e2be80a3d4ee8f.jpg|http://car.com/static/upload/da5027ed2e527824/308d61230a2b89c4.jpg|http://car.com/static/upload/b5e62130ea8e52f0/27083167e50092a9.jpg|http://car.com/static/upload/adec2f4fcdd27f34/3bb8d5340d087cc3.jpg
//            $savedata['goods_image'] = "http://chexinyuan.com/static/upload/car/".$v['cate_id']."/1.jpg|http://chexinyuan.com/static/upload/car/".$v['cate_id']."/2.jpg|http://chexinyuan.com/static/upload/car/".$v['cate_id']."/3.jpg|http://chexinyuan.com/static/upload/car/".$v['cate_id']."/4.jpg";
//            $r = DataService::save("Goods",$savedata,'id');
//            if(!$r){
//                echo 222;
//                exit();
//            }
//        }
//        echo 123;
//    }

}
