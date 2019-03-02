<?php

$content = $_GET['codes'] ? rtrim($_GET['codes']) : '';
include_once __DIR__ . '/extend/BCode/BCGFontFile.php';
include_once __DIR__ . '/extend/BCode/BCGDrawing.php';
include_once __DIR__ . '/extend/BCode/BCGcode39.barcode.php';

// $code = '';
// 加载字体大小
//$font = new BCGFontFile('./class/font/Arial.ttf', 18);

//颜色条形码
$color_black = new \BCGColor(0, 0, 0);
$color_white = new \BCGColor(255, 255, 255);

$drawException = null;
try {
    $code = new \BCGcode39();
    $code->setScale(2);
    $code->setThickness(30); // 条形码的厚度
    $code->setForegroundColor($color_black); // 条形码颜色
    $code->setBackgroundColor($color_white); // 空白间隙颜色
    // $code->setFont($font); //
    $code->parse($content); // 条形码需要的数据内容
} catch (\Exception $exception) {
    $drawException = $exception;
}

//根据以上条件绘制条形码
$drawing = new \BCGDrawing('', $color_white);
if ($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code);
    $drawing->draw();
}

// 生成PNG格式的图片
header('Content-Type: image/png');
// header('Content-Disposition:attachment; filename="barcode.png"'); //自动下载
$drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);