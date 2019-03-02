<?php /*a:2:{s:56:"E:\car_subsystem/application/admin\view\index\index.html";i:1536303516;s:56:"E:\car_subsystem/application/admin\view\public\main.html";i:1534260517;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta name="renderer" content="webkit">
        <title><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); if(!empty($title)): ?> · <?php endif; ?><?php echo sysconf('site_name'); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link href="http://chexinyuan.com/clz.ico" rel="shortcut icon">
        <link href="/static/plugs/awesome/css/font-awesome.min.css?v=<?php echo date('ymd'); ?>" rel="stylesheet">
        <link href="/static/plugs/bootstrap/css/bootstrap.min.css?v=<?php echo date('ymd'); ?>" rel="stylesheet">
        <link href="/static/plugs/layui/css/layui.css?v=<?php echo date('ymd'); ?>" rel="stylesheet">
        <link href="/static/theme/css/console.css?v=<?php echo date('ymd'); ?>" rel="stylesheet">
        <link href="/static/theme/css/animate.css?v=<?php echo date('ymd'); ?>" rel="stylesheet">
        
        <script>window.ROOT_URL = '';</script>
        <script src="/static/plugs/jquery/pace.min.js"></script>
        <script src="/static/plugs/layui/layui.all.js"></script>
        <script src="/static/admin.js"></script>
    </head>
    <body class="framework mini">
        

<!-- 顶部菜单区域 开始 -->
<div class="framework-topbar">
    <div class="topbar-head pull-left">
        <a href="<?php echo url('@'); ?>" class="topbar-logo pull-left"><?php echo sysconf('app_name'); ?> <sup><?php echo sysconf('app_version'); ?></sup></a>
    </div>
    <!--<?php foreach($menus as $oneMenu): ?>-->
    <a data-menu-node="m-<?php echo htmlentities($oneMenu['id']); ?>" data-open="<?php echo htmlentities($oneMenu['url']); ?>" class="topbar-btn pull-left transition">
        <?php if(!(empty($oneMenu['icon']) || (($oneMenu['icon'] instanceof \think\Collection || $oneMenu['icon'] instanceof \think\Paginator ) && $oneMenu['icon']->isEmpty()))): ?><span class='font-s13 <?php echo htmlentities($oneMenu['icon']); ?>'></span>&nbsp;<?php endif; ?>
        <?php echo htmlentities((isset($oneMenu['title']) && ($oneMenu['title'] !== '')?$oneMenu['title']:'')); ?>
    </a>
    <!--<?php endforeach; ?>-->
    <div class="pull-right">
        <!--<?php if(session('user.id')): ?>-->
        <div class="dropdown">
            <a href="#" class="dropdown-toggle topbar-btn text-center transition" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user font-s13"></span>
                <?php echo session('user.username'); ?>
                <span class="toggle-icon glyphicon glyphicon-menu-up transition font-s13"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="topbar-btn"><a data-modal="<?php echo url('admin/index/bind_weixin'); ?>?id=<?php echo session('user.id'); ?>" data-title="绑定微信帐号"><i class="glyphicon glyphicon-lock"></i> 绑定微信帐号</a></li>
                <li class="topbar-btn"><a data-modal="<?php echo url('admin/index/pass'); ?>?id=<?php echo session('user.id'); ?>" data-title="修改密码"><i class="glyphicon glyphicon-lock"></i> 修改密码</a></li>
                <li class="topbar-btn"><a data-modal="<?php echo url('admin/index/info'); ?>?id=<?php echo session('user.id'); ?>" data-title="修改资料"><i class="glyphicon glyphicon-edit"></i> 修改资料</a></li>
                <li class="topbar-btn">
                    <a data-load="<?php echo url('admin/login/out'); ?>" data-confirm="确定要退出登录吗？"><i class="glyphicon glyphicon-log-out"></i> 退出登录</a>
                </li>
            </ul>
        </div>
        <!--<?php else: ?>-->
        <div class="topbar-info-item">
            <a data-href="<?php echo url('@admin/login'); ?>" data-toggle="dropdown" class=" topbar-btn text-center">
                <span class='glyphicon glyphicon-user'></span> 立即登录 </span>
            </a>
        </div>
        <!--<?php endif; ?>-->
    </div>
    <a class="topbar-btn pull-right transition" data-tips-text="刷新" data-reload="true" style="width:50px"><span class="glyphicon glyphicon-refresh font-s12"></span></a>
</div>
<!-- 顶部菜单区域 结束 -->

<!-- 左则菜单区域 开始 -->
<div class="framework-leftbar">
    <?php foreach($menus as $oneMenu): ?>
    <!--<?php if(!(empty($oneMenu['sub']) || (($oneMenu['sub'] instanceof \think\Collection || $oneMenu['sub'] instanceof \think\Paginator ) && $oneMenu['sub']->isEmpty()))): ?>-->
    <div class="leftbar-container hide notselect" data-menu-layout="m-<?php echo htmlentities($oneMenu['id']); ?>">
        <div class="line-top">
            <i class="layui-icon font-s12">&#xe65f;</i>
        </div>
        <?php foreach($oneMenu['sub'] as $twoMenu): ?>
        <!--<?php if(empty($twoMenu['sub']) || (($twoMenu['sub'] instanceof \think\Collection || $twoMenu['sub'] instanceof \think\Paginator ) && $twoMenu['sub']->isEmpty())): ?>-->
        <a class='transition' data-menu-node="m-<?php echo htmlentities($oneMenu['id']); ?>-<?php echo htmlentities($twoMenu['id']); ?>" data-open="<?php echo htmlentities($twoMenu['url']); ?>">
            <?php if(!(empty($twoMenu['icon']) || (($twoMenu['icon'] instanceof \think\Collection || $twoMenu['icon'] instanceof \think\Paginator ) && $twoMenu['icon']->isEmpty()))): ?><span class='<?php echo htmlentities($twoMenu['icon']); ?> font-icon'></span>&nbsp;<?php endif; ?>
            <?php echo htmlentities($twoMenu['title']); ?>
        </a>
        <!--<?php else: ?>-->
        <div data-submenu-layout='m-<?php echo htmlentities($oneMenu['id']); ?>-<?php echo htmlentities($twoMenu['id']); ?>'>
            <a class='menu-title transition'>
                <?php if(!(empty($twoMenu['icon']) || (($twoMenu['icon'] instanceof \think\Collection || $twoMenu['icon'] instanceof \think\Paginator ) && $twoMenu['icon']->isEmpty()))): ?><span class='<?php echo htmlentities($twoMenu['icon']); ?> font-icon'></span>&nbsp;<?php endif; ?>
                <?php echo htmlentities($twoMenu['title']); ?> <i class='layui-icon pull-right font-s12 transition'>&#xe619;</i>
            </a>
            <div>
                <?php foreach($twoMenu['sub'] as $thrMenu): if(strpos($thrMenu['url'],'http')!==false){ ?>
                <a class='transition click_iframe' href="<?php echo htmlentities($thrMenu['url']); ?>" target="myiframe" data-menu-node="m-<?php echo htmlentities($oneMenu['id']); ?>-<?php echo htmlentities($twoMenu['id']); ?>-<?php echo htmlentities($thrMenu['id']); ?>">
                    <?php if(!(empty($thrMenu['icon']) || (($thrMenu['icon'] instanceof \think\Collection || $thrMenu['icon'] instanceof \think\Paginator ) && $thrMenu['icon']->isEmpty()))): ?><span class='<?php echo htmlentities($thrMenu['icon']); ?> font-icon'></span><?php endif; ?> <?php echo htmlentities($thrMenu['title']); ?>
                </a>
                <?php }else{ ?>
                <a class='transition click_open' data-open="<?php echo htmlentities($thrMenu['url']); ?>" data-menu-node="m-<?php echo htmlentities($oneMenu['id']); ?>-<?php echo htmlentities($twoMenu['id']); ?>-<?php echo htmlentities($thrMenu['id']); ?>">
                    <?php if(!(empty($thrMenu['icon']) || (($thrMenu['icon'] instanceof \think\Collection || $thrMenu['icon'] instanceof \think\Paginator ) && $thrMenu['icon']->isEmpty()))): ?><span class='<?php echo htmlentities($thrMenu['icon']); ?> font-icon'></span><?php endif; ?> <?php echo htmlentities($thrMenu['title']); ?>
                </a>
                <?php } endforeach; ?>
            </div>
        </div>
        <!--<?php endif; ?>-->
        <?php endforeach; ?>
    </div>
    <!--<?php endif; ?>-->
    <?php endforeach; ?>
</div>
<!-- 左则菜单区域 结束 -->

<!-- 右则内容区域 开始 -->
<div class="notification sticky hide">
    <p id="content"> </p>
    <a class="close" href="javascript:"> <img src="/static/icon-close.png" /></a>
</div>
<div class="framework-body"></div>
<iframe name="myiframe" id="myiframe" src=""  frameborder="0" align="left" width="85%" height="90%" style="display: none">
    <p>你的浏览器不支持iframe标签</p>
</iframe>
<!-- 右则内容区域 结束 -->
<style>
    #myiframe{
        right: 0;
        bottom: 0;
        top: 50px;
        left: 220px;
        position: fixed;
        overflow-y: scroll;
        z-index: 1;
    }
</style>
<script>
    $(".click_iframe").on('click',function () {
        $(".framework-body").hide();
        $("#myiframe").show();
    })

    $(".click_open").on('click',function () {
        $("#myiframe").hide();
        $(".framework-body").show();
    })
</script>
<script src='http://cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script>
<script src='/static/notify.js'></script>
<link href="/static/main.css" rel="stylesheet" type="text/css" />
<script>
    /* <?php if((null !== app('session')->get('user.id')) && app('session')->get('user.id') != 0): ?> */
    // 连接服务端
    var socket = io('http://chexinyuan.com:2120');
    // uid可以是自己网站的用户id，以便针对uid推送以及统计在线人数
    uid = '<?php echo htmlentities(app('session')->get('user.id')); ?>';
    // socket连接后以uid登录
    socket.on('connect', function(){
        socket.emit('login', uid);
    });
    // 后端推送来消息时
    socket.on('new_msg', function(data){
        data = JSON.parse(data);
        $('#content').html('收到消息：'+data.msg);
        $('.notification.sticky').notify();
    });
    // 使用人数
    //    socket.on('update_online_count', function(online_stat){
    //        console.log(online_stat);
    //    });
    /*  <?php endif; ?> */
</script>

        <script src="/static/plugs/require/require.js"></script>
        <script src="/static/app.js"></script>
        
    </body>
</html>