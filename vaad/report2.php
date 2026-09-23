<?php
if($_POST['from_date']!='') {
  $search_from_date=$_POST['from_date'];
}
if($_POST['to_date']!='') {
  $search_to_date=$_POST['to_date'];
}
if($search_from_date!='' && $search_to_date!='') {
   $where=" and schedule_date>='$search_from_date' and schedule_date<='$search_to_date'";
} else {
   $where="";
}

$report1="select id,activity_no,party,department,dhara,ref_lawer,department,schedule_date,result_date,cur_discuss_date,next_discss_date,property_desc FROM addcase WHERE case_status='COMPLETED' ".$where." order by id desc";
$res = $obj->reportData($report1);
?>
<div id="con_container">
<!--User Detail Start-->
<div id="user_detail">
<?php include("reportheader.php"); ?>
</div>
<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div id="summary">
  <form name="addmrf" method="post" action="">
   <table  height="0" cellpadding="5" cellspacing="1"  id="gradient-style" class="tablecolor" style="width:800px">
     <tr>
       <td>From Date :</td>
       <td><input  name="from_date" id="from_date" value="<?=$search_from_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
       <td>To Date</td>
       <td><input  name="to_date" id="to_date" value="<?=$search_to_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
       <td><input type="submit" name="submit" value="search"></td>
     </tr>
   </table>
   </form>
   <br/>
   
	<table  height="0" cellpadding="5" cellspacing="1"  id="gradient-style" class="tablecolor" style="width:930px">
  <tr bgcolor="#de8f30" class="theight">
  <th width="5%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>d-la-</strong></th>
  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>izdj.k la0</strong></th>
  <th width="20%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>i{kdkj </strong></th>
  <th width="15%"  align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>uxj fuxe vuqHkkx</strong></th>
  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>U;k;ky;@/kkjk</strong></th>  
  <th width="15%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fu;r frfFk</strong></th> 
  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fu.kZ; frfFk</strong></th> 
  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>lEcfU/kr odhy</strong></th>
  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>lEifRr fooj.k </strong></th>
  </tr>
<?php
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($res))
  {
?>
  <tr>
   <td align="center" class="newtxt1"><?=$row->id?></td>
    <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$row->activity_no?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=stripslashes($row->party)?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=stripslashes($obj->getName('department',$row->department))?></td>
    <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=stripslashes($row->dhara)?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?php echo strftime("%d-%m-%Y", strtotime($row->schedule_date));?></td>
  <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?php echo strftime("%d-%m-%Y", strtotime($row->result_date));?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=stripslashes($obj->getName('advocate',$row->ref_lawer))?></td>
   <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=stripslashes($row->property_desc)?></td>
    </tr>
 <?php $sn++; $total++;} ?> 
    </tbody>
</table>
</div>
<div>Total : <?=$total?>&nbsp; &nbsp;&nbsp;&nbsp;
 <a href="javascript:void();" onclick="javascript:window.open('print_report_completed.php?from_date=<?=$search_from_date?>&to_date=<?=$search_to_date?>','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');">Print</a>
</div>
</div><br />
</div>
</div>
<SCRIPT language="JavaScript">
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"from_date",
			dateFormat:"%Y-%m-%d"
			}),
			new JsDatePick({
			useMode:2,
			target:"to_date",
			dateFormat:"%Y-%m-%d"
			});
		};
 </SCRIPT>