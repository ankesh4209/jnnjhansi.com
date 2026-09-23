<?php
 $new_status=0;
 $laywer_id='';
if($_POST['submit']!='') {
  if($_POST['from_date']!='') {
    $search_from_date=$_POST['from_date'];
  }
  if($_POST['to_date']!='') {
    $search_to_date=$_POST['to_date'];
  }
  
  $laywer_id=$_POST['lawyer_id'];
   
  if($_POST['status']=='0') {
    $status='';
    $new_status=0;
  } else {
    $new_status=$_POST['status'];
    $status=" AND case_status='".$_POST['status']."'";
  }
  
  if($search_from_date!='' && $search_to_date!='') {
     $where=" and schedule_date>='$search_from_date' and schedule_date<='$search_to_date'";
  } else {
     $where="";
  }
  
 $report1="select id,activity_no,party,department,dhara,ref_lawer,department,schedule_date,result_date,cur_discuss_date,next_discss_date FROM addcase WHERE ref_lawer='".$laywer_id."' ".$status." ".$where." order by id desc";
 $res = $obj->reportData($report1);
   
 
}
$res2 =$obj->showdata('advocate','id DESC','','','');
 
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
   <table  height="0" cellpadding="5" cellspacing="1"  id="gradient-style" class="tablecolor" style="width:900px">
     <tr>
       <td>From Date :</td>
       <td><input  name="from_date" id="from_date" value="<?=$search_from_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
       <td>To Date</td>
       <td><input  name="to_date" id="to_date" value="<?=$search_to_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
       <td style="font-family: kruti_dev_010regular;font-size:18px;">odhy</td>
       <td >
        <select name="lawyer_id" style='font-family: kruti_dev_010regular;font-size:18px;' selected='selected'>
           <?php 
            while($row2 = mysql_fetch_array($res2)) {
              if($laywer_id==$row2['id']) {
                echo"<option value='".$row2['id']."' style='font-family: kruti_dev_010regular;font-size:18px;' selected='selected'>".stripslashes($row2['name'])."</option>";
              } else {
                echo"<option value='".$row2['id']."' style='font-family: kruti_dev_010regular;font-size:18px;'>".stripslashes($row2['name'])."</option>";
              } 
            }
           ?>
        </select>
       </td>
       <td>Status</td>
       <td>
        <select name="status">                               
           <option value="0" <?php if($new_status=='0') {echo"selected";} ?> >All</option>
           <option value="PENDING" <?php if($new_status=='PENDING') {echo"selected";} ?> >PENDING</option>
           <option value="COMPLETED" <?php if($new_status=='COMPLETED') {echo"COMPLETED";} ?> >COMPLETED</option>
        </select>
       </td>
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

    </tr>
 <?php $sn++; $total++;} ?> 
    </tbody>
</table>
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