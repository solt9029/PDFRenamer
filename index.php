<?php
include "vendor/autoload.php";

mkdir("../prod");

//一つ上の階層のPDFファイルをすべて取得する
foreach(glob("../*.pdf") as $filename){
	$parser=new \Smalot\PdfParser\Parser();

	//PDFのバージョンによっては対応してない場合があるらしい悲しい
	try{
		$pdf=$parser->parseFile($filename);

		//タイトルを取得する
		$details=$pdf->getDetails();
		$title=$details["Title"];
		
	}catch(Exception $e){
		$pdf=file_get_contents($filename);

		//タイトルら辺の部分取り出す
		$start_pos=strpos($pdf,"<dc:title>")+strlen("<dc:title>");
		$length=strpos($pdf,"</dc:title>")-$start_pos;
		$dc_title_part=substr($pdf,$start_pos,$length);

		//ちゃんと取り出す
		$start_pos=strpos($dc_title_part,'<rdf:li xml:lang="x-default">')+strlen('<rdf:li xml:lang="x-default">');
		$length=strpos($dc_title_part,"</rdf:li>")-$start_pos;
		$title=substr($dc_title_part,$start_pos,$length);
	}

	$title=str_replace(array(":","/","\n","?","*","<",">","|",'"')," ",$title); //ファイル名として使用できない文字列を置換(windows)

	//タイトルが見つからなかった場合など
	if(strlen($title)>100 || $title==null || $title===""){
		$title=$filename;
	}else{
		//同じ名前のファイルがあった場合にcopyを付け足す処理
		foreach(glob("../prod/*.pdf") as $f){
			$start_pos=strlen("../prod/");
			$length=strpos($f,".pdf")-$start_pos;
			$f=substr($f,$start_pos,$length);
			if($f===$title){
				$title.="_copy";
			}
		}
		$title="../prod/".$title.".pdf"; //良い感じにタイトルが取れてるならprodフォルダに突っ込む
	}

	

	rename($filename,$title); //ファイル名を変更する
}
