<?php

include "vendor/autoload.php";

$parser=new \Smalot\PdfParser\Parser();
$pdf=$parser->parseFile("document.pdf");
$details=$pdf->getDetails();
$title=$details["Title"];

var_dump($details);

echo $title;