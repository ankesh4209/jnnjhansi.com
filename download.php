<?php
$file = basename($_GET['file'] ?? '');
$docRoot = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/') : __DIR__;
$fullpath = $docRoot . '/excel/';
if (!file_exists($fullpath . $file)) {
    $fullpath = __DIR__ . '/excel/';
}
  header("Expires: 0");  
  header("Cache-Control: no-store, no-cache, must-revalidate");  
  header("Cache-Control: post-check=0, pre-check=0", false);  
  header("Pragma: no-cache");  
  header("Content-Type: application/vnd.ms-excel; charset=UTF-8");  
  // tell file size  
  header('Content-length: '.filesize($fullpath.$file));  
  // set file name  
  header("Content-disposition: attachment; filename=".basename($file)."");  
  readfile($fullpath.$file);  
 /*if(is_file($fullpath.$file))
 {
	@unlink($fullpath.$file);
 }*/
  //echo $fullpath.$file;
 // Exit script. So that no useless data is output-ed.  
 exit;  
?>
