<?php
$span1="display:none";
$span2="display:none";
$span3="display:none";
$span4="display:none";

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

if(!empty($_POST['sub']))
{
$msg="";


if($_POST['report1']=="" && $_POST['report2']=="" && $_POST['report3']=="" && $_POST['report4']=="")
	{
     $report1 ="SELECT * FROM license_data ORDER BY id desc";
	}
else
{
$filter='';
if(!empty($_POST['report1'])) 
if($_POST['report1']=='URRANGE') $span1="";
if(!empty($_POST['ur_from_date']) and !empty($_POST['ur_to_date']))
$filter.=" updated_date BETWEEN '".$_POST['ur_from_date']."' AND '".$_POST['ur_to_date']."'";
if($_POST['report1']=='URALL')
{
$filter.=" AND updated_date is not null";
} 

if(!empty($_POST['report2']))
if($_POST['report2']=='ARALL') 
{ 
$filter.=" AND amount is not null";
} 
if($_POST['report2']=='ARRANGE')
{
$span2="";
$filter.=" AND reg_date BETWEEN '".$_POST['min_amt_date']."' AND '".$_POST['max_amt_date']."'";

$report_amtsql ="select count(license_no) as number,sum(amount) as amount from license_data where reg_date 
BETWEEN '".$_POST['min_amt_date']."' AND '".$_POST['max_amt_date']."' group by reg_date  order by updated_date desc";
$res_amt = $obj->reportData($report_amtsql);
$num_amt   = mysql_num_rows($res_amt);
$res_amt_res = $obj->reportData($report_amtsql);
}

if(!empty($_POST['report3'])) 
if($_POST['report3']=='CRALL') 
{ 
$filter.=" AND license_type is not null";
} 
if($_POST['report3']=='CRRANGE')
{
$span3="";
if($_POST['category']=='VEH'){
$filter.=" AND license_type='".$_POST['category']."'";
}
if($_POST['category']=='LND'){
$filter.=" AND license_type='".$_POST['category']."'";
}
}

if(!empty($_POST['report4']))
if($_POST['report4']=='RENEW') {
// financial year report
 $span4="";
$fin_start = $dateAr['start']->format('Y-m-d');
$fin_end   = $dateAr['end']->format('Y-m-d');
$filter.=" AND renew='RN' AND reg_date BETWEEN '".$fin_start."' AND '".$fin_end."'";
}
if($_POST['report4']=='NEW') 
{
$fin_start = $dateAr['start']->format('Y-m-d');
$fin_end   = $dateAr['end']->format('Y-m-d');
$filter.=" AND renew is NULL AND reg_date BETWEEN '".$fin_start."' AND '".$fin_end."'";
} 

//echo"Filter111->".$filter;	
$filter = str_replace("WHERE AND","WHERE",'WHERE'.$filter);
$report1 ="SELECT * FROM license_data $filter ORDER BY id desc";
}
//print_r($_POST);
$res1 = $obj->reportData($report1);
$num   = mysql_num_rows($res1);
$res = $obj->reportData($report1);
//for amount date

}

?>
<div id="con_container">
<!--User Detail Start-->

<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div id="summary">
 <form method="POST" name="reportfrm" id="reportfrm" onsubmit="return ValidateForm();">
<table width="800" border="0" cellspacing="0" cellpadding="4">
  <tr>
	<td align="center" valign="top" align="left" width="300">Date wise registered user report</td>
	<td width="500">All&nbsp;&nbsp;&nbsp;<input type="radio" name="report1" id="report1" value="URALL" <?php if($_POST['report1']=='URALL') {echo "checked";}?>>&nbsp;Range&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="report1" id="report1" value="URRANGE" <?php if($_POST['report1']=='URRANGE') {echo "checked";}?>><span id="ur_date" style="<?php echo $span1;?>">&nbsp;From<input type="text" name="ur_from_date" id="ur_from_date" value="<?php echo $_POST['ur_from_date'];?>" class="txtarea_Mid ipbutton"/>&nbsp;To<input type="text" name="ur_to_date" id="ur_to_date" value="<?php echo $_POST['ur_to_date'];?>" class="txtarea_Mid ipbutton"/></span></td>
	</tr>
	<tr>
	<td align="center" valign="top" align="left" width="300">Amount wise report</td>
	<td width="500">All&nbsp;&nbsp;&nbsp;<input type="radio" name="report2" id="report2" value="ARALL" <?php if($_POST['report2']=='ARALL') {echo "checked";}?>>&nbsp;Range&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="report2" id="report2" value="ARRANGE" <?php if($_POST['report2']=='ARRANGE') {echo "checked";}?>>
	<span id="ar_amount" style="<?php echo $span2;?>">&nbsp;From<input name="min_amt_date"  id="min_amt_date" value="<?php echo $_POST['min_amt_date'];?>" size="8" type="text" class="txtarea_Mid ipbutton"/>&nbsp;To<input name="max_amt_date" id="max_amt_date" value="<?php echo $_POST['max_amt_date'];?>" size="8" type="text" class="txtarea_Mid ipbutton"/></span></td>  
	</tr>
	<tr>
	<td align="center" valign="top" align="left" width="300">Category wise report</td>
	<td width="500">All&nbsp;&nbsp;&nbsp;<input type="radio" name="report3" id="report3" value="CRALL" <?php if($_POST['report3']=='CRALL') {echo "checked";}?>>&nbsp;Specific&nbsp;&nbsp;&nbsp;<input type="radio" name="report3" id="report3" value="CRRANGE" <?php if($_POST['report3']=='CRRANGE') {echo "checked";}?>>&nbsp;
	<span id="cat_rep" style="<?php echo $span3;?>">Category&nbsp;<select  name="category" id="category" >
     <option value="0" >Select Type</option>
            <option value="LND" <?php if($_POST['category']=='LND'){echo "selected";}?>>Land</option><option value="VEH" <?php if($_POST['category']=='VEH'){echo "selected";}?>>Vehicle</option></select></span>
	</td>
	</tr>
	<tr>
	<td align="center" valign="top" align="left" width="300">Financial year new/renewal report</td>
	<td width="500">New<input type="radio" name="report4" id="report4" value="NEW" <?php if($_POST['report4']=='NEW') {echo "checked";}?>>Renew&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="report4" id="report4" value="RENEW" <?php if($_POST['report4']=='RENEW') {echo "checked";}?>>&nbsp;
	</td>
	</tr>
  <tr>
  <td colspan="3" align="center"><input type="submit" name="sub" class="btn" value="Report"> &nbsp; <input type="reset" name="reset" class="btn" value="Reset"></td>
  </tr>
 </table> </form>
  <table width="950px" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td  align="center" valign="top" width="950px">
	<table height="0" width="950px" cellpadding="2" cellspacing="1"  bgcolor="#FFFFFF" id="gradient-style"  >
	<?php if($num_amt>0){?>
   <?php
  while($row_amt = mysql_fetch_object($res_amt_res))
  {
  $tot_amt = $tot_amt + $row_amt->amount;
  } ?>
<tr>
  <td colspan="0" align="center" style="font-family: kruti_dev_010regular;font-size:18px;">jkf&ldquo;k</td>
  <td colspan="0" align="center" style="background-color:#FF9900; color:#FF0000;"><?=$tot_amt;?></th>
    </tr>
  <?php }?>

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
<?php if((!empty($_POST['sub']))&&($num<=0)){?><tr><td colspan="11" style="background-color:#FF9900; color:#FF0000;" align="center">No Records found!</td></tr><?php }?>
</table>
</div>
</div><br />
</div>
</div>
<script language="javascript">
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"ur_from_date",
			dateFormat:"%Y-%m-%d"
			}),

			new JsDatePick({
			useMode:2,
			target:"ur_to_date",
			dateFormat:"%Y-%m-%d"
			}),
			new JsDatePick({
			useMode:2,
			target:"min_amt_date",
			dateFormat:"%Y-%m-%d"
			}),

			new JsDatePick({
			useMode:2,
			target:"max_amt_date",
			dateFormat:"%Y-%m-%d"
			})
			
		};
</script>
<SCRIPT language="JavaScript">
$("input[type=radio]").click(function() {
    // Alter the text of the span to the text of the clicked label.
	var chkval = $(this).val();
	
	if(chkval=="URALL")    { $('#ur_date').hide(); $('#ur_from_date').val(''); $('#ur_to_date').val('');}
    if(chkval=="URRANGE")  { $('#ur_date').show()}

   // For amount
	if(chkval=="ARALL")    { $('#ar_amount').hide(); $('#min_amt_date').val(''); $('#max_amt_date').val(''); }

	if(chkval=="ARRANGE")  { $('#ar_amount').show() }

	//for category
    if(chkval=="CRALL")    { $('#cat_rep').hide(); $('#category').val('0');}

	if(chkval=="CRRANGE")  { $('#cat_rep').show() }

});
</script>
<script language="javascript">
function ValidateForm()
{
var rep1 = $("input[name=report1]:checked").val();
if(rep1 =='URRANGE')
{
if(document.getElementById('ur_from_date').value=='' || document.getElementById('ur_to_date').value=='')
{
alert("Date range can not be blank!");
return false;
}
}
var rep2 = $("input[name=report2]:checked").val();
if(rep2 =='ARRANGE')
{
if(document.getElementById('min_amt_date').value=='' || document.getElementById('max_amt_date').value=='')
{
alert("Date range can not be blank!");
return false;
}
}
var rep3 = $("input[name=report3]:checked").val();
if(rep3 =='CRRANGE')
{
if(document.getElementById('category').value=='0')
{
alert("Category can not be blank!");
return false;
}
}

}

</script>