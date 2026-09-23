<?php
session_start();
ini_set('display_errors', 'Off');
error_reporting(0);
include_once('class.hrrecruitment.php');
include_once('number2word.php');
$obj = new recruitment();
class MyDateTime extends DateTime
{
    /**
    * Calculates start and end date of fiscal year
    * @param DateTime $dateToCheck A date withn the year to check
    * @return array('start' => timestamp of start date ,'end' => timestamp of end date) 
    */
    public function fiscalYear()
    {
        $result = array();
        $start = new DateTime();
        $start->setTime(0, 0, 0);
        $end = new DateTime();
        $end->setTime(23, 59, 59);
        $year = $this->format('Y');
        $start->setDate($year, 4, 1);
        if($start <= $this){
            $end->setDate($year +1, 3, 31);
        } else {
            $start->setDate($year - 1, 4, 1);
            $end->setDate($year, 3, 31);
        }
        $result['start'] = $start;
        $result['end'] = $end;
        return $result;
    }
}
$obj1 = new MyDateTime();
$dateAr = $obj1->fiscalYear();
//print_r($_POST);
$report1= $_SESSION['report1'];
$res1 = $obj->reportData($report1);
$num   = mysql_num_rows($res1);
$res = $obj->reportData($report1);
//for amount date
$res_amt_res = $obj->reportData($report1);

?>
<?php include_once("utility.html"); ?>
<div id="con_container">
<!--User Detail Start-->

<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div id="summary">
  <table width="950px" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td  align="center" valign="top" width="950px">
	<table height="0" width="950px" cellpadding="2" cellspacing="1"  bgcolor="#FFFFFF" id="gradient-style"  >
  <tr width="950px">
    <th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">la[;k</span></strong></th>
	<th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">ykbZlsUl la[;k</span></strong></th>
	<th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">ykbZlsUl dk izdkj</span></strong></th>
	<th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">jkf&ldquo;k</span></strong></th>
	<th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">uke</span></strong></th>
	<th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">fuokl</span></strong></th>
	<th  align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">fnukad</span></strong></th>
    </tr>
<?php
  $sn=1;
  $total=0;
  if($res){
  while($row = mysql_fetch_object($res))
  {
?>
  <tr>
  <td colspan="0" align="center"><?=$sn?></td>
	<td colspan="0" align="center"><?=$row->license_no?></td>
	<td colspan="0" align="center" style="font-family: kruti_dev_010regular;font-size:16px;"><?php if($row->license_type=="LND"){echo"izfr&#8217;Bku";}else{echo"okgu";}?></span></th>
	<td colspan="0" align="center"><?=$row->amount?></th>
	<td colspan="0" align="center" style="font-family: kruti_dev_010regular;font-size:16px;"><?=$row->name?></th>
	<td colspan="0" align="center" style="font-family: kruti_dev_010regular;font-size:16px;"><?=$row->address?></th>
	<td colspan="0" align="center"><?php echo strftime("%d-%m-%Y", strtotime($row->reg_date));?></th>

    </tr>
 <?php $sn++; $total++;} } ?> 
    </tbody>
</td>
  </tr>
  <?php
   $tot_amt=0;
   while($row_amt = mysql_fetch_object($res_amt_res))
  {
  $tot_amt = $tot_amt + $row_amt->amount;
  } ?>
<tr>
  <td colspan="3" align="center" style="font-family: kruti_dev_010regular;font-size:18px;background-color:#FF9900; color:#FF0000;">jkf&ldquo;k</td>
  <td colspan="4" align="left" style="background-color:#FF9900; color:#FF0000;"><?=$tot_amt;?></th>
    </tr>
<?php if((!empty($_POST['sub']))&&($num<=0)){?><tr><td colspan="11" style="background-color:#FF9900; color:#FF0000;" align="center">No Records found!</td></tr><?php }?>
</table>
<p align="center" id="pbutton">
    <input style="background:#000066; color:#FFFFFF;  padding:5px; border:none; font-size:11px;" type="button" value="Print" id="pprint" name="pprint" onClick="printPage()">
  </p>
</div>
</div><br />
</div>
</div>
<script language="javascript">
function printPage()
{
document.getElementById('pprint').style.display = 'none';
window.print()
self.close();
}
</script>