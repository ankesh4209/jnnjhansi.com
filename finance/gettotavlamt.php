<?php 
include_once('class.finance.php');
$obj = new finance();
$mid     = $_GET['mid']; 
$workdesc = $_GET['workdesc'];
$finalAmt =0;
//$acno= $obj->getacnoAjax('mad','bank_acno',$bid);
$totavamt = $obj->getTotalAvaMadamt($workdesc,$mid);
//$finalAmt = $acno."#".$totavamt;
$finalAmt = $totavamt;
if($mid)
{
echo "$finalAmt";
}
else
{
echo "$finalAmt";
}
?>