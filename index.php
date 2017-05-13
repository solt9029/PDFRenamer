<?php

error_reporting(E_ALL);

include "vendor/autoload.php";

//一つ上の階層のPDFファイルをすべて取得する
foreach(glob("../*.pdf") as $filename){
	$parser=new \Smalot\PdfParser\Parser();
	$pdf=$parser->parseFile($filename);
	$details=$pdf->getDetails();
	$title=$details["Title"];
	//ファイル名として使用できない文字列を置換(windows)
	$title=str_replace(array(":","/","\n","?","*","<",">","|",'"')," ",$title);
	rename($filename,"../".$title.".pdf");
}