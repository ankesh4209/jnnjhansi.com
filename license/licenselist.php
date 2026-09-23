<?php
$id=$_GET['id'];
$task=$_GET['task'];
if(!empty($_GET['stval'])) $sbu_dis1='disabled';
if(!empty($_GET['sid'])) $status_dis='disabled';
if(!empty($id) and $task=='del')
{
$aff=$obj->delete('license_data',$id);	
if($aff>0) header("location:index.php?page=licenselist&msg=$id has been deleted successfully");
}

 if(isset($_GET['pg']))
		{	
			$pg = $_GET['pg'];
		}
		else
		{
			$pg = 1;
		}
     	$limit=50;
		$pagetoshow = 10;
		$startlimit =($pg - 1) * $limit;

    $nqr="SELECT * FROM license_data";
    $rec =$obj->reportData($nqr);
    $num=mysql_num_rows($rec);

    $qur="SELECT * FROM license_data order by id desc"; 
    $qur.=" limit $startlimit,$limit";
    $link = "index.php?page=licenselist";

    $edit_link = "pg=".$pg; 	
    $tot_num_pages = ceil($num/$limit);
    $res_new=$obj->reportData($qur);

?>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>

<div id="con_container">
<!--User Detail Start-->
<div id="user_detail">
<p>
<form name="frm" method="post"  onsubmit="return filtervalid();" enctype="application/x-www-form-urlencoded">
<table width="100%" border="0" cellspacing="5" cellpadding="5">
</table>
</form>
</p>
</div>
<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div align="left">&nbsp;&nbsp;&nbsp;
 <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B3E0FF" id="table19" >
 <tr>
 <td colspan="10">
 </td>
 </tr>
  <tr bgcolor="#D9EBFF">
  <td width="10%" align="center" class="newtxt1"><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">ykbZlsUl la[;k</span></strong></td>
  <td width="10%" align="center" class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">ykbZlsUl dk izdkj</span></strong></td>
  <td width="10%" align="center" class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">izdkj</span></strong></td>
  <td width="10%" align="center" class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">jkf&ldquo;k</span></strong></td>
  <td width="20%"  align="center" class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">uke </span></strong></td>
  <td width="40%" align="center" class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">fuokl </span></strong></td>
  <td width="8%" align="center" class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">fnukad</span></strong></td>
  <td colspan="4" width="10%" align="center"><span class="newtxt1"><strong><strong><span style="font-family: kruti_dev_010regular;font-size:20px;">fu;a=.k</span></strong></span></td>
  </tr>
  <?php 
  
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($res_new))
  {
 if($row->license_type=="LND"){
 $print="<a href=\"javascript:void();\" onclick=\"javascript:window.open('print_land.php?id=$row->id&pb=vi','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');\"><img src=\"images/loa.png\" alt=\"Print\" title=\"Print\" border=\"0\" width=\"16\" height=\"16\"></a>";

  $view="<a href=\"javascript:void();\" onclick=\"javascript:window.open('print_land.php?id=$row->id','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');\"><img src=\"images/preview.png\" alt=\"View\" title=\"View\" border=\"0\" width=\"16\" height=\"16\"></a>";
 }
 else
 {
$print="<a href=\"javascript:void();\" onclick=\"javascript:window.open('print_veh.php?id=$row->id&pb=vi','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');\"><img src=\"images/loa.png\" alt=\"Print\" title=\"Print\" border=\"0\" width=\"16\" height=\"16\"></a>";

 $view="<a href=\"javascript:void();\" onclick=\"javascript:window.open('print_veh.php?id=$row->id','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');\"><img src=\"images/preview.png\" alt=\"View\" title=\"View\" border=\"0\" width=\"16\" height=\"16\"></a>";
  
 }

  $receipt="<a href=\"javascript:void();\" onclick=\"javascript:window.open('receipt.php?id=$row->id','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');\"><img src=\"images/rcpt.png\" alt=\"Receipt\" title=\"Receipt \" border=\"0\" width=\"16\" height=\"16\"></a>";
   //$status=($row->status=='closed')?'<img src="images/close_bt.gif" title="Open" />':'<img src="images/open_bt.gif" title="Closed" />';
 $loc_qr="SELECT * FROM location where  id=".$row->location;
 $loc_rec =$obj->reportData($loc_qr);
 $loc_row = mysql_fetch_object($loc_rec);
 

?>
  <tr bgcolor="<?=$row_color?>">
    <td align="center" class="newtxt1 mrfcode"><a href="index.php?page=addlicense&task=edit&id=<?=$row->id?>" <?php if($row->final_submit=='YES' && $_SESSION['usertype']!='SUPER' ) {?> onClick="javascript:alert('अनुमति अस्वीकृत. सुपर व्यवस्थापक से संपर्क करें?');return false;" <?php } ?>  title="Click to Edit this record"><?=$row->license_no?></a></td>
    <td height="25" align="center" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:20px;"><?php if($row->license_type=="LND"){echo"izfr&#8217;Bku";}else{echo"okgu";}?></span></td>
    <td height="25" align="center" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:20px;"><?php echo str_replace("\\","",stripslashes($loc_row->name)); ?></span></td>
	<td height="25" align="center" class="newtxt1"><?=$row->amount?></td>
    <td height="25" align="center" style="font-family: kruti_dev_010regular;font-size:18px;"><?=str_replace("\\","",stripslashes($row->name));?></td>
    <td height="25" align="center" style="font-family: kruti_dev_010regular;font-size:18px;"><?=str_replace("\\","",stripslashes($row->address));?></td>
    <td align="center" class="newtxt1"><?php echo strftime("%d-%m-%Y", strtotime($row->updated_date));?></td>
	<td align="center" class="newtxt1"><?=$print?></td>
	<td align="center" class="newtxt1"><?=$view?></td>
	<td align="center" class="newtxt1"><?=$receipt?></td>
	<td align="center" class="newtxt1">
  <a style="font-family: kruti_dev_010regular;font-size:18px;" href="index.php?page=licenselist&id=<?=$row->id?>&task=del" <?php if($_SESSION['usertype']=='SUPER'){?> onClick="javascript:return confirm('Are you sure to delete?-<?=$row->id?>?');"  title=""<?php } else { ?>onClick="javascript:alert('Please contact admin!');return false;" title="Permission Denied" <?php } ?> >jn</a></td>
  </tr>
  <?php $sn++; $total++;} ?>
   <tr  bgcolor="#D9EBFF"><td colspan="6" align="center"></td><td align="center" class="newtxt1" bgcolor="#FF9933" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>dqy</strong></td><td bgcolor="#FF9933" class="newtxt1" align="center"><span class="style1"><?=$total?></span></td>
   <td colspan="8"></td></tr>
  <?php if($num<1){ ?>
  <tr style="background-color:#FF9900; color:#FF0000;" class="newtxt1"><td colspan="8" align="center">No Record</td></tr>
  <?php } ?>
 <tr><td colspan="11" align="center">
		    <?php	
			$pagestring = paging($pg,$tot_num_pages,$pagetoshow,$link);
			echo $pagestring;
			?>
		   </td><tr>
    </table>
  </div>
</div>
</div>
</div>
<script language="javascript">
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"startdate",
			dateFormat:"%Y-%m-%d",
			limitToToday:true
			});
			new JsDatePick({
			useMode:2,
			target:"enddate",
			dateFormat:"%Y-%m-%d",
			limitToToday:true
			});
		};
function filtervalid()
{
var fname = document.frm;
if(fname.startdate.value=="" && fname.enddate.value=="" && fname.status.value=="" && fname.emp_sbu.value=="" && fname.position.value=="" && fname.recruiter.value=="" && fname.location.value=="")
{
alert("Please select atleast one parameter.");
fname.startdate.focus();
return false;	
}
if((fname.startdate.value!='') && (fname.enddate.value==""))
{
alert("Please select enddate.");
fname.enddate.focus();
return false;
}	
}		
</script>