<?php
define("CACHE_PATH", dirname(__FILE__)."/.cache");
define("CACHE_LIFETIME", isset($_GET["cache_lifetime"])?$_GET["cache_lifetime"]:12000);
$path = $_GET["path"];
$document_root = $_SERVER["DOCUMENT_ROOT"];

// take off the filename portion of the requested file
$base = basename($path);
// determine what the width and height should be
$suffix = substr($base, strrpos($base,"_")+1);
list($dims, $ext) = explode(".", $suffix, 2);
list($width, $height) = explode("x",$dims);
if (!is_numeric($width) || !is_numeric($height)){
	header("HTTP/1.0 404 Not Found");
	exit();
}

$srcPath = $document_root.substr($path, 0,strrpos($path, "_")).".".$ext;
$cachePath = CACHE_PATH.substr($path, 0,strrpos($path, "_")).".".$ext."/".$width."x".$height.".".$ext;
if (!file_exists($srcPath)){
	// if the source image is not found return a 404
	header("HTTP/1.0 404 Not Found");
	exit();
}
if (!file_exists(dirname($cachePath))){
	 mkdir(dirname($cachePath), 0755, true) || die("Couldn't create $cachePath");
}

if (!file_exists($cachePath) || filemtime($cachePath)<filemtime($srcPath)){
	$im = new Imagick($srcPath);
	if ($width == 0 || $height == 0){
		if ($width == 0){
			$width = $im->getImageHeight() * ($im->getImageHeight()/$height);
		} else {
			$height = $im->getImageWidth() * ($im->getImageWidth()/$width);
		}
	}
	$im->adaptiveResizeImage($width, $height, true);
	file_put_contents($cachePath, $im->getImageBlob());
}
header("Content-Type: ".mime_content_type($cachePath));
header("Content-Length: ".filesize($cachePath));
header("Pragma: public");
header("Cache-Control: max-age=".CACHE_LIFETIME.", public, must-revalidate");
header("Expires: " . gmdate("D, d M Y H:i:s", time()+CACHE_LIFETIME)." GMT");

$fp = fopen($cachePath, "r");
fpassthru($fp);

