<?php

function create_word2pdf($lastfnamedoc, $lastfnamepdf)
{
//    $word = new COM("word.application") or die("Can't start Word!");
//    // set it to 1 to see the MS Word window (the actual opening of the document)
//    $word->Visible = 0;
//    // recommend to set to 0, disables alerts like "Do you want MS Word to be the default .. etc"
//    $word->DisplayAlerts = 0;
//    // open the word 2007-2013 document
//    // $word->Documents->Open('3.docx');
//    // $wordname='D:/www/fa/3.doc';
//    $word->Documents->Open("E:/car_subsystem/HelloWorld.docx");
//    // save it as word 2003
//    //  $word->ActiveDocument->SaveAs('4.doc');
//    // convert word 2007-2013 to PDF
//    // $pdfname='D:/www/fa/3.pdf';
//    $word->ActiveDocument->ExportAsFixedFormat($lastfnamepdf, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);
//    // quit the Word process
//    $word->Quit(false);
//    // clean up

    // 建立一个指向新COM组件的索引
    $word = new COM("word.application") or die("Can't start Word!");
//打开一个文档
    $word->Documents->OPen("E:/car_subsystem/1.doc");
//读取文档内容
    $test= $word->ActiveDocument->content->Text;
    echo $test;
    echo "<br>";
// 关闭与COM组件之间的连接
    $word->Quit();
}
error_reporting(E_ALL);

$lastfnamedoc = 'E:/car_subsystem/1.docx';
$lastfnamepdf = 'E:/car_subsystem/HelloWorld.pdf';
create_word2pdf($lastfnamedoc, $lastfnamepdf);
