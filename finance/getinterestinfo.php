<?php 
include_once('class.finance.php');
$obj = new finance();
$bid     = $_GET['bid']; 
$finalAmt =0;
$acno= $obj->getacnoAjax('bank','bank_acno',$bid);
$totavamt = $obj->getTotalAvaAmt('bank',$bid);
$finalAmt = $acno."#".$totavamt;
if($bid)
{
echo "$finalAmt";
}
else
{
echo "$finalAmt";
}
?>