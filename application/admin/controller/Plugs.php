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
use service\FileService;
use think\Image;

/**
 * 插件助手控制器
 * Class Plugs
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/21
 */
class Plugs extends BasicAdmin
{
    /**
     * 文件上传
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upfile()
    {
        $uptype = $this->request->get('uptype');
        if (!in_array($uptype, ['local', 'qiniu', 'oss'])) {
            $uptype = sysconf('storage_type');
        }
        $mode = $this->request->get('mode', 'one');
        $types = $this->request->get('type', 'jpg,png');
        $this->assign('mimes', FileService::getFileMine($types));
        $this->assign('field', $this->request->get('field', 'file'));
        return $this->fetch('', ['mode' => $mode, 'types' => $types, 'uptype' => $uptype]);
    }

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
            return json(['code' => 'ERROR', 'msg' => '文件上传类型受限']);
        }
        $ext = strtolower(pathinfo($file->getInfo('name'), 4));
        $names = str_split($this->request->post('md5'), 16);
        $filename = "{$names[0]}/{$names[1]}.{$ext}";
        // 文件上传Token验证
        if ($this->request->post('token') !== md5($filename . session_id())) {
            return json(['code' => 'ERROR', 'msg' => '文件上传验证失败']);
        }
        // 文件上传处理
        if (($info = $file->move("static/upload/{$names[0]}", "{$names[1]}.{$ext}", true))) {
            if (($site_url = FileService::getFileUrl($filename, 'local'))) {
                $gets = $this->request->request();
                if(isset($gets['water']) && $gets['water'] ==1){
                    //添加水印
                    $image = Image::open("./static/upload/{$names[0]}/{$names[1]}.{$ext}");
//                // 给原图设置水印图片（colleced.png）并保存water_image.png
//                $image->tilewater('./static/images/water.png',30)->save("./static/upload/{$names[0]}/{$names[1]}.{$ext}");

                    // 给原图左上角添加水印并保存water_image.png
                    $image->text('车厘子汽车交付中心|0755-28363331','wryh.ttf',20,'#ffffff',Image::WATER_SOUTHEAST ,-30,0)->save("./static/upload/{$names[0]}/{$names[1]}.{$ext}");
                }
                return json(['data' => ['site_url' => $site_url], 'code' => 'SUCCESS', 'msg' => '文件上传成功']);
            }
        }
        return json(['code' => 'ERROR', 'msg' => '文件上传失败']);
    }

    /**
     * 文件状态检查
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upstate()
    {
        $post = $this->request->post();
        $filename = join('/', str_split($post['md5'], 16)) . '.' . strtolower(pathinfo($post['filename'], 4));

        // 检查文件是否已上传
        if (($site_url = FileService::getFileUrl($filename))) {
            $this->result(['site_url' => $site_url], 'IS_FOUND');
        }
        // 需要上传文件，生成上传配置参数
        $config = ['uptype' => $post['uptype'], 'file_url' => $filename];

        switch (strtolower($post['uptype'])) {
            case 'local':
                $config['server'] = FileService::getUploadLocalUrl();
                $config['token'] = md5($filename . session_id());
                break;
            case 'qiniu':
                $config['server'] = FileService::getUploadQiniuUrl(true);
                $config['token'] = $this->_getQiniuToken($filename);
                break;
            case 'oss':
                $time = time() + 3600;
                $policyText = [
                    'expiration' => date('Y-m-d', $time) . 'T' . date('H:i:s', $time) . '.000Z',
                    'conditions' => [['content-length-range', 0, 1048576000]],
                ];
                $config['server'] = FileService::getUploadOssUrl();
                $config['policy'] = base64_encode(json_encode($policyText));
                $config['site_url'] = FileService::getBaseUriOss() . $filename;
                $config['signature'] = base64_encode(hash_hmac('sha1', $config['policy'], sysconf('storage_oss_secret'), true));
                $config['OSSAccessKeyId'] = sysconf('storage_oss_keyid');
        }
        $this->result($config, 'NOT_FOUND');
    }

    /**
     * 生成七牛文件上传Token
     * @param string $key
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _getQiniuToken($key)
    {
        $baseUrl = FileService::getBaseUriQiniu();
        $bucket = sysconf('storage_qiniu_bucket');
        $accessKey = sysconf('storage_qiniu_access_key');
        $secretKey = sysconf('storage_qiniu_secret_key');
        $params = [
            "scope"      => "{$bucket}:{$key}", "deadline" => 3600 + time(),
            "returnBody" => "{\"data\":{\"site_url\":\"{$baseUrl}/$(key)\",\"file_url\":\"$(key)\"}, \"code\": \"SUCCESS\"}",
        ];
        $data = str_replace(['+', '/'], ['-', '_'], base64_encode(json_encode($params)));
        return $accessKey . ':' . str_replace(['+', '/'], ['-', '_'], base64_encode(hash_hmac('sha1', $data, $secretKey, true))) . ':' . $data;
    }

    /**
     * 字体图标选择器
     * @return \think\response\View
     */
    public function icon()
    {
        $field = $this->request->get('field', 'icon');
        return $this->fetch('', ['field' => $field]);
    }

}
