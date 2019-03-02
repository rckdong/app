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

namespace app\index\controller;

use controller\BasicApi;
use service\HttpService;
use think\App;
use think\Db;
use service\OfficeService;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Word extends BasicApi
{

    public function test()
    {
        $url = "http://chexinyuan.com/index.php/index/word/create_word?contract_id=10";
        HttpService::get($url, [], ['timeout' => 1]);
//        $r = HttpService::get($url);
//        print_r($r);
    }


    //h:1143/480
    public function testimage()
    {
        $image = \think\Image::open('./static/dingche.png');
        $daxie = $this->get_amount(1596);
        $image->text($daxie, 'wryh.ttf', 14, '#000000', [416, 659]);
        print_r($daxie);
        $image->water('./static/dianzi.png', [480, 1030], 50)->save('./static/water_image.png');
        $width = $image->width();
        print_r($width);
    }

    public function create_word()
    {
        set_time_limit(0);
        $ttf = 'wryh.ttf';
        $get = $this->request->request();
        if (!isset($get['contract_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['contract_id'];
        $contact_info = Db::name("Contacts")
            ->alias("c")
            ->join("system_user", "system_user.id = c.saler_id", "LEFT")
            ->join("store", "store.id = c.store_id", "LEFT")
            ->where("c.id", $contact_id)
            ->field("c.*,system_user.name as saler_name,store.name as store_name,store.phone as store_phone,store.address as store_address")
            ->find();
        if (!$contact_info) {
            $this->error_json(400, '合同不存在，请稍后重试1', []);
        }
        //读取文件
        if ($contact_info['contract_type'] == 1) {
            $file = "./static/daigou.png";
        } else {
            $file = "./static/dingche.png";
        }

        //订金大写
        $contact_info['big_deposit_price'] = $this->get_amount($contact_info['deposit_price']);
        //订购总价大写
        $contact_info['big_transaction_price'] = $this->get_amount($contact_info['transaction_price']);
        $image = \think\Image::open($file);
        if($contact_info['contract_type'] == 1){
            $image->text($contact_info['big_deposit_price'], $ttf, 14, '#000000', [418, 604]);
            $image->text($contact_info['big_transaction_price'], $ttf, 14, '#000000', [418, 634]);
            $image->text($contact_info['store_name'], $ttf, 14, '#000000', [474, 80]);
            $image->text($contact_info['nickname'], $ttf, 10, '#000000', [122, 142]);
            $image->text($contact_info['identify'], $ttf, 10, '#000000', [82, 172]);
            $image->text($contact_info['phone'], $ttf, 10, '#000000', [82, 198]);
            $image->text($contact_info['address'], $ttf, 10, '#000000', [82, 232]);
            $image->text($contact_info['saler_name'], $ttf, 10, '#000000', [438, 172]);
            $image->text($contact_info['store_phone'], $ttf, 10, '#000000', [438, 198]);
            $image->text($contact_info['store_address'], $ttf, 10, '#000000', [438, 232]);
            $image->text($contact_info['brand_name'], $ttf, 10, '#000000', [128, 456]);
            $image->text($contact_info['guidance_price'], $ttf, 10, '#000000', [351, 456]);
            $image->text($contact_info['car_model'], $ttf, 10, '#000000', [128, 486]);
            $image->text($contact_info['car_color'], $ttf, 10, '#000000', [128, 520]);
            $image->text($contact_info['car_selection'], $ttf, 10, '#000000', [128, 564]);
            $image->text($contact_info['deposit_price'], $ttf, 10, '#000000', [128, 604]);
            $image->text($contact_info['transaction_price'], $ttf, 10, '#000000', [128, 634]);
            $image->text($contact_info['buy_type_desc'], $ttf, 10, '#de0505', [248, 735]);
            $image->text('编号：'.$contact_info['contract_number'], $ttf, 10, '#de0505', [463, 107]);

            if ($contact_info['car_type'] == 1) {
                $image->text("√", $ttf, 10, '#000000', [25, 413]);
            } else {
                $image->text("√", $ttf, 10, '#000000', [25, 391]);
            }

            if ($contact_info['buy_type'] == 0) {
                $image->text("√", $ttf, 10, '#000000', [25, 734]);
            } else {
                $image->text("√", $ttf, 10, '#000000', [160, 734]);
            }

            $image->text("√", $ttf, 10, '#000000', [350, 517]);

            if ($contact_info['insurance_ids'] != '') {
                $image->text("√", $ttf, 10, '#000000', [431, 517]);
            }

            if ($contact_info['purchase_tax'] == 1) {
                $image->text("√", $ttf, 10, '#000000', [497, 517]);
            }

            if ($contact_info['buy_type'] == 0) {
                $image->text("√", $ttf, 10, '#000000', [350, 539]);
            }

            if ($contact_info['license']!= '') {
                $image->text("√", $ttf, 10, '#000000', [431, 539]);
            }

        }else{
            $image->text($contact_info['big_deposit_price'], $ttf, 14, '#000000', [418, 658]);
            $image->text($contact_info['big_transaction_price'], $ttf, 14, '#000000', [418, 687]);
            $image->text($contact_info['store_name'], $ttf, 14, '#000000', [474, 130]);
            $image->text($contact_info['nickname'], $ttf, 10, '#000000', [122, 192]);
            $image->text($contact_info['identify'], $ttf, 10, '#000000', [82, 222]);
            $image->text($contact_info['phone'], $ttf, 10, '#000000', [82, 248]);
            $image->text($contact_info['address'], $ttf, 10, '#000000', [82, 282]);
            $image->text($contact_info['saler_name'], $ttf, 10, '#000000', [438, 222]);
            $image->text($contact_info['store_phone'], $ttf, 10, '#000000', [438, 248]);
            $image->text($contact_info['store_address'], $ttf, 10, '#000000', [438, 282]);
            $image->text($contact_info['brand_name'], $ttf, 10, '#000000', [128, 506]);
            $image->text($contact_info['guidance_price'], $ttf, 10, '#000000', [351, 506]);
            $image->text($contact_info['car_model'], $ttf, 10, '#000000', [128, 536]);
            $image->text($contact_info['car_color'], $ttf, 10, '#000000', [128, 570]);
            $image->text($contact_info['car_selection'], $ttf, 10, '#000000', [128, 614]);
            $image->text($contact_info['deposit_price'], $ttf, 10, '#000000', [128, 658]);
            $image->text($contact_info['transaction_price'], $ttf, 10, '#000000', [128, 687]);
            $image->text($contact_info['buy_type_desc'], $ttf, 10, '#de0505', [248, 785]);
            $image->text("1", $ttf, 10, '#000000', [563, 506]);
            $image->text("待定", $ttf, 10, '#000000', [351, 536]);
            $image->text("待定", $ttf, 10, '#000000', [563, 536]);
            $image->text($contact_info['buy_type_desc'], $ttf, 10, '#de0505', [248, 785]);
            $image->text($contact_info['buy_type_desc'], $ttf, 10, '#de0505', [248, 785]);
            $image->text('编号：'.$contact_info['contract_number'], $ttf, 10, '#de0505', [463, 157]);

            if ($contact_info['car_type'] == 1) {
                $image->text("√", $ttf, 10, '#000000', [25, 463]);
            } else {
                $image->text("√", $ttf, 10, '#000000', [25, 441]);
            }

            if ($contact_info['buy_type'] == 0) {
                $image->text("√", $ttf, 10, '#000000', [25, 784]);
            } else {
                $image->text("√", $ttf, 10, '#000000', [160, 784]);
            }

            $image->text("√", $ttf, 10, '#000000', [350, 567]);

            if ($contact_info['insurance_ids'] != '') {
                $image->text("√", $ttf, 10, '#000000', [431, 567]);
            }

            if ($contact_info['purchase_tax'] == 1) {
                $image->text("√", $ttf, 10, '#000000', [497, 567]);
            }

            if ($contact_info['buy_type'] == 0) {
                $image->text("√", $ttf, 10, '#000000', [350, 589]);
            }

            if ($contact_info['license']!= '') {
                $image->text("√", $ttf, 10, '#000000', [431, 589]);
            }
        }
        $image->save('./static/pdf/contract_' . $contact_info["contract_number"] . '.png');
        exit(json_encode(['status' => 200, 'msg' => '成功']));
    }

    /**
     * 生成word文档
     */
    public function create_word2()
    {
        set_time_limit(0);
        $get = $this->request->request();
        if (!isset($get['contract_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['contract_id'];
        $contact_info = Db::name("Contacts")
            ->alias("c")
            ->join("system_user", "system_user.id = c.saler_id", "LEFT")
            ->join("store", "store.id = c.store_id", "LEFT")
            ->where("c.id", $contact_id)
            ->field("c.*,system_user.name as saler_name,store.name as store_name,store.phone as store_phone,store.address as store_address")
            ->find();
        if (!$contact_info) {
            $this->error_json(400, '合同不存在，请稍后重试1', []);
        }
        //读取文件
        if ($contact_info['contract_type'] == 1) {
            $file = __DIR__ . "/resouce/word.docx";
        } else {
            $file = __DIR__ . "/resouce/dinggou.docx";
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

        $contact_info['big_deposit_price'] = $this->get_amount($contact_info['deposit_price']);
        $contact_info['big_transaction_price'] = $this->get_amount($contact_info['transaction_price']);

        // Simple table
        $templateProcessor->setValue('mendian', $contact_info['store_name'] ?: '');
        $templateProcessor->setValue('nickname', $contact_info['nickname'] ?: '');
        $templateProcessor->setValue('contract_number', $contact_info['contract_number'] ?: '');
        $templateProcessor->setValue('identify', $contact_info['identify'] ?: '');
        $templateProcessor->setValue('phone', $contact_info['phone'] ?: '');
        $templateProcessor->setValue('address', $contact_info['address'] ?: '');
        $templateProcessor->setValue('saler_name', $contact_info['saler_name'] ?: '');
        $templateProcessor->setValue('mendian_phone', $contact_info['store_phone'] ?: '');
        $templateProcessor->setValue('mendian_address', $contact_info['store_address'] ?: '');
        $templateProcessor->setValue('brand_name', $contact_info['brand_name'] ?: '');
        $templateProcessor->setValue('guidance_price', $contact_info['guidance_price'] ?: '');
        $templateProcessor->setValue('car_model', $contact_info['car_model'] ?: '');
        $templateProcessor->setValue('car_color', $contact_info['car_color'] ?: '');
        $templateProcessor->setValue('car_selection', $contact_info['car_selection'] ?: '');
        $templateProcessor->setValue('deposit_price', $contact_info['deposit_price'] ?: '');
        $templateProcessor->setValue('big_deposit_price', $contact_info['big_deposit_price'] ?: '');
        $templateProcessor->setValue('transaction_price', $contact_info['transaction_price'] ?: '');
        $templateProcessor->setValue('big_transaction_price', $contact_info['big_transaction_price'] ?: '');
        $templateProcessor->setValue('pay_type_desc', $contact_info['buy_type_desc'] ?: '');

        if ($contact_info['car_type'] == 1) {
            $templateProcessor->setValue('car_type_false', "√");
            $templateProcessor->setValue('car_type_true', "口");
        } else {
            $templateProcessor->setValue('car_type_true', "√");
            $templateProcessor->setValue('car_type_false', "口");
        }


        if ($contact_info['buy_type'] == 0) {
            $templateProcessor->setValue('pay_type_false', "√");
            $templateProcessor->setValue('pay_type_true', "口");
        } else {
            $templateProcessor->setValue('pay_type_true', "√");
            $templateProcessor->setValue('pay_type_false', "口");
        }

        $templateProcessor->setValue('is_pay', "√");

        if ($contact_info['insurance_ids'] != '') {
            $templateProcessor->setValue('is_insurance', "√");
        } else {
            $templateProcessor->setValue('is_insurance', "口");
        }

        if ($contact_info['purchase_tax'] == 1) {
            $templateProcessor->setValue('purchase_tax', "√");
        } else {
            $templateProcessor->setValue('purchase_tax', "口");
        }

        if ($contact_info['buy_type'] == 0) {
            $templateProcessor->setValue('buy_type', "口");
        } else {
            $templateProcessor->setValue('buy_type', "√");
        }

        if ($contact_info['license'] != '') {
            $templateProcessor->setValue('license', "√");
        } else {
            $templateProcessor->setValue('license', "口");
        }

//生成文件
        $name = "static/pdf/Contract_" . $contact_info['contract_number'] . ".docx";
        $templateProcessor->saveAs($name);
        $r = $this->create_word2pdf($name, 'static/pdf/contract_' . $contact_info["contract_number"] . '.pdf');
        if (!$r) {
            exit(json_encode(['status' => 500, 'msg' => '不成功']));
        }
        exit(json_encode(['status' => 200, 'msg' => '成功']));
    }

    public function create_word2pdf($lastfnamedoc, $lastfnamepdf)
    {
        $lastfnamedoc = env("root_path") . $lastfnamedoc;
        $lastfnamepdf = env("root_path") . $lastfnamepdf;
        $OfficeService = new OfficeService();
        $r = $OfficeService->run($lastfnamedoc, $lastfnamepdf);
        if ($r > 0) {
            unlink($lastfnamedoc);
            return true;
        } else {
            return false;
        }
    }


    /**
     * 金额小写转大写
     * @param $num
     * @return string
     */
    public function get_amount($num)
    {
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        $num = round($num, 2);
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "数据太长，没有这么大的钱吧，检查下";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            $num = $num / 10;
            $num = (int)$num;
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            $m = substr($c, $j, 6);
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }

        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        if (empty($c)) {
            return "零元整";
        } else {
            return $c . "整";
        }
    }
}
