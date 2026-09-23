<?php 
include_once('class.finance.php');
$obj = new finance();
$bid     = $_GET['bid']; 
$acno =0;
$acno= $obj->getacnoAjax('bank','bank_acno',$bid);
if($bid)
{
echo "$acno";
}
else
{
echo "$acno";
}
?>