<?php
$id=$_GET['id'];   
$task=$_GET['task'];
$name="";
$view="display:none";
if(!empty($id))
{
$r =$obj->showdata('addcase','',$id);	
$row = mysql_fetch_object($r);
$orderno= $row->orderno;
$activity_no = $row->activity_no;
$party = stripslashes($row->party);
$property_desc = stripslashes($row->property_desc);
$department = stripslashes($row->department);
$court = stripslashes($row->court);
$dhara = stripslashes($row->dhara);
$schedule_date = $row->schedule_date;
$result_date=$row->result_date;
$ref_lawer=stripslashes($row->ref_lawer);
$description=stripslashes($row->description);
$case_status=$row->case_status;
$reason_status=stripslashes($row->reason_status);
$last_date = $row->last_date;
$cur_discuss_date= $row->cur_discuss_date;
$comments= stripslashes($row->comments);
$next_discss_date= $row->next_discss_date;
if(!empty($result_date)) $view="";
//if(empty($result_date)) {$result_date=date('Y-m-d');}
}
//$targetdate= date("Y-m-d",mktime(0,0,0,date('m')+1,date('d'),date('Y')));
if(!empty($_POST['Save']))
{
if(!empty($id) and $task=='edit')
$n=$obj->saveCaseData($id);
else
$n=$obj->saveCaseData();
if($n>0) header("location:index.php?page=caselist&msg=Data saved successfully");
}
?>
 <!--User Detail Start-->
     <form name="addmrf" method="post" action="">
	 <input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
	 <!-- latter removed fields -->	 
        <!-- End latter removed fields -->          
		<div id="user_detail">
          <h3 style="margin:0; padding:0;">Add/Edit Municipal Corporation Cases</h3>
       </div>
        <!--User Detail End-->
        <div id="content_area">
          <div class="height_adj">
            <div align="left">&nbsp;&nbsp;&nbsp;
              <table width="100%" cellpadding="10" cellspacing="0" border="0" bgcolor="#B3E0FF" >
			  <tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">d-la-</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input name="orderno" id="orderno" value="<?=$orderno?>" type="text" class="newtxt" style="font-family: kruti_dev_010regular;font-size:15px;"/></td>
                </tr>
				 <tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">izdj.k la0</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="activity_no" id="activity_no" value="<?=$activity_no?>" type="text" class="newtxt" style="font-family: kruti_dev_010regular;font-size:15px;"/></td>
                </tr>
				 <tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">i{kdkj</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="party" id="party" value="<?=$party?>" type="text" class="newtxt" style="font-family: kruti_dev_010regular;font-size:15px;"/></td>
                </tr>
				<tr>
                  <td width="25%" colspan="1" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">lEifRr fooj.k</span></td>
               <td width="25%" colspan="3" align="left" bgcolor="#E8F6FF"><textarea name="property_desc" id="property_desc" style="font-family: 'kruti_dev_010regular';font-size:15px;width:180px;" rows="5" cols="8"><?=$property_desc?></textarea></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">	foHkkx</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF">
               <select  name="department" id="department" style="font-family: kruti_dev_010regular;font-size:18px;width:180px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:15px;">foHkkx </option>
            <?php echo getDropDownPageWise('department',$row->department);?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">	U;k;ky;</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF">
               <select  name="court" id="court" style="font-family: kruti_dev_010regular;font-size:18px; width:180px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:15px;">U;k;ky; </option>
            <?php echo getDropDownPageWise('court',$row->court);?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">	U;k;ky;@/kkjk</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="dhara" id="dhara" value="<?=$dhara?>" type="text" class="newtxt" style="font-family: kruti_dev_010regular;font-size:15px;"/></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">fu;r frfFk</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="schedule_date" id="schedule_date" value="<?=$schedule_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>

				<tr id="resDate" style="<?php echo $view?>">
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">fu.kZ; frfFk</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="result_date" id="result_date" value="<?=$result_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
				
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">	lEcfU/kr odhy</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF">
               <select  name="ref_lawer" id="ref_lawer" style="font-family: kruti_dev_010regular;font-size:18px;width:180px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:15px;">lEcfU/kr odhy</option>
            <?php echo getDropDownPageWise('advocate',$row->ref_lawer);?></select></td>
                </tr>
				<tr>
                  <td width="25%" colspan="1" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">fo'ks"k fooj.k</span></td>
               <td width="25%" colspan="3" align="left" bgcolor="#E8F6FF"><textarea name="description" id="description" style="font-family: 'kruti_dev_010regular';font-size:15px;width:180px;" rows="5" cols="8"><?=$description?></textarea></td>
                </tr>
				<tr>
                  <td width="25%" colspan="1" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">fLFkr</span></td>
               <td width="25%" colspan="3" align="left" bgcolor="#E8F6FF"><select onchange="getDecidedDate(this.value)" name="case_status" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" >
				<option value="0" style="font-family: kruti_dev_010regular;font-size:18px;">fLFkr</option>
				<option value="PENDING" style="font-family: kruti_dev_010regular;font-size:18px;" <?php if($row->case_status=="PENDING"){echo"selected";}?>>yfEcr</option> 
				<option value="COMPLETED" style="font-family: kruti_dev_010regular;font-size:18px;" <?php if($row->case_status=="COMPLETED"){echo"selected";}?>>fu.khZr</option> 
				</select></td>
                </tr>
				<?php if(!empty($id)){?>
				<!--<tr>
                  <td width="25%" colspan="1" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">lEifRr fooj.k</span></td>
               <td width="25%" colspan="3" align="left" bgcolor="#E8F6FF"><textarea name="reason_status" id="reason_status" style="font-family: 'kruti_dev_010regular';font-size:15px;width:180px;" rows="5" cols="8"><?=$reason_status?></textarea></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">fiNyh rkjh[k</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="last_date" id="last_date" value="<?=$last_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">vkt rkjh[k</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="cur_discuss_date" id="cur_discuss_date" value="<?=$cur_discuss_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
                 <tr>
                  <td width="25%" colspan="1" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">fooj.k</span></td>
               <td width="25%" colspan="3" align="left" bgcolor="#E8F6FF"><textarea name="comments" id="comments" style="font-family: 'kruti_dev_010regular';font-size:15px;width:180px;" rows="5" cols="8"><?=$comments?></textarea></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">fu.kZ; vxyh rkjh[k</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="next_discss_date" id="next_discss_date" value="<?=$next_discss_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>   -->
				<?php }?>
              </table>
              <div>
                <div align="center"><br />
                  <input name="back" type="reset"  class="btn" style="font-family: kruti_dev_010regular;font-size:18px;" value="nqckjk" />&nbsp;&nbsp;<input style="font-family: kruti_dev_010regular;font-size:18px;" name="Save" type="submit" class="btn" value="cpk"/>                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
</form>
<SCRIPT language="JavaScript">
function getDecidedDate(getval){
					
if(getval=='COMPLETED')  
{
	
document.getElementById('resDate').style.display="";
}
if(getval=='PENDING')  
{
document.getElementById('resDate').style.display="none";
}

}
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"schedule_date",
			dateFormat:"%Y-%m-%d"
			}),
			new JsDatePick({
			useMode:2,
			target:"result_date",
			dateFormat:"%Y-%m-%d"
			}),
            new JsDatePick({
			useMode:2,
			target:"last_date",
			dateFormat:"%Y-%m-%d"
			}),
            new JsDatePick({
			useMode:2,
			target:"cur_discuss_date",
			dateFormat:"%Y-%m-%d"
			}),
             new JsDatePick({
			useMode:2,
			target:"next_discss_date",
			dateFormat:"%Y-%m-%d"
			});
			
		};
var frmvalidator  = new Validator("addmrf");
frmvalidator.addValidation("orderno","req","Please enter order no.!");
frmvalidator.addValidation("activity_no","req","Please enter no!");
frmvalidator.addValidation("party","req","Please enter party!");
frmvalidator.addValidation("property_desc","req","Please enter property description!");
frmvalidator.addValidation("department","dontselect=0","Please select department!");
frmvalidator.addValidation("court","dontselect=0","Please select court!");
frmvalidator.addValidation("dhara","req","Please enter dhara!");
frmvalidator.addValidation("schedule_date","req","Please select schedule date.!");
frmvalidator.addValidation("ref_lawer","dontselect=0","Please select advocate!");
 </SCRIPT>