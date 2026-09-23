<?php
$id=$_GET['id'];   
$task=$_GET['task'];
$name="";
$dddis="display:none";
$chkdis="display:none";
$fineiddis="display:none";
$disiddis ="display:none";
$landloc  ="display:none";
//vech display
$vh1dis="display:none";
$vh2dis="display:none";
$btdis= "display:none";
//$renewdis="display:none";
//$reg_date=date('y-m-d');
if(!empty($id))
{
$r =$obj->showdata('license_data','',$id);	
$row = mysql_fetch_object($r);
$license_type= $row->license_type;
//for license type
if($license_type=='VEH'){$vh1dis=""; $vh2dis="";}
if($license_type=='LND'){$btdis="";}
$renewdis="";
$name = $row->name;
$father_name = $row->father_name;
$license_no= $row->license_no;
$amount = $row->amount;
$amount_in_words = $row->amount_in_words;
$address = $row->address;
$mobno = $row->mobno;
$amount_in_words = $row->amount_in_words;
$amount = $row->amount;
$reg_date = $row->reg_date;
$exp_date = $row->exp_date;
$updated_date = $row->updated_date;
$reg_by = $row->reg_by;
$btype = $row->btype;
$category=$row->category;
$location=$row->location;
$situate = $row->situate;
if($location) $landloc  ="";
//$billno=$row->billno;
$vehicle_type=$row->vehicle_type;
$vehicle_area= $row->vehicle_area;
$vehicle_no= $row->vehicle_no;
$vehicle_reg= $row->vehicle_reg;
$dis_type= $row->dis_type;
$fine_amt= $row->fine_amt;
$dis_amt= $row->dis_amt;
//****display discount fine display
if($dis_type=='FINE'){$fineiddis="";}
if($dis_type=='DIS'){$disiddis="";}

$pay_type= $row->pay_type;
//******payment type display
if($pay_type=='DD'){$dddis='';}
if($pay_type=='CHECK'){$chkdis='';}
$chk_no= $row->chk_no;
$dd_no= $row->dd_no;
$renew= $row->renew;
}

$targetdate= date("Y-m-d",mktime(0,0,0,date('m')+1,date('d'),date('Y')));
if(!empty($_POST['Save']))
{
if(!empty($id) and $task=='edit'){
$n=$obj->saveLicenseData($id);
header("location:index.php?page=licenslist&msg=Save Successfully!");
}
else{
$n=$obj->saveLicenseData();
if($n>0) header("location:index.php?page=licenslist&msg=Save Successfully!");
}
}
?>
 <!--User Detail Start-->
     <form name="addmrf" method="post">
	 <input type="hidden" name="user_id" value="<?=$_SESSION['userid']?>">
	 <!-- latter removed fields -->	 
        <!-- End latter removed fields -->          
		<div id="user_detail">
          <h3 style="margin:0; padding:0;"><span style="font-family: kruti_dev_010regular;font-size:25px;">jftLVªs”ku</span><?php if(!empty($_GET['id'])){echo "-<strong>$license_no</strong>";}?></h3>
       </div>
        <!--User Detail End-->
        <div id="content_area">
          <div class="height_adj">
            <div align="left">&nbsp;&nbsp;&nbsp;
              <table width="100%" cellpadding="3" cellspacing="0" bgcolor="#B3E0FF" >
              <tr>
                 <td width="25%" colspan="0" align="left" bgcolor="#E8F6FF" class="newtxt1"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">ykbZlsUl dk izdkj</span></strong></td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><span style="font-family: kruti_dev_010regular;font-size:18px;">izfr&#8217;Bku</span><input type="radio" onclick="javascript:loadMe('LND')" name="license_type" id="license_type" value="LND" <?php if($row->license_type=='LND'){echo"checked";}?>> &nbsp; &nbsp;<span style="font-family: kruti_dev_010regular;font-size:18px;">okgu</span>  <input type="radio" onclick="javascript:loadMe('VEH')" name="license_type" id="license_type" value="VEH" <?php if($row->license_type=='VEH'){echo"checked";}?>> &nbsp; &nbsp;<?php if(!empty($_GET['id'])){?><td width="25%" colspan="0" align="left" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">uohdj.k</td><td width="25%" colspan="0" align="left" bgcolor="#E8F6FF" class="newtxt1" ><input type="checkbox" onchange="chkval();" name="renew" id="renew" value="RN" <?php if($renew=="RN"){echo"checked";}?>></td><?php }?></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">ykbZlsUl Js.kh</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><label>
          <select  name="category" id="category" style="font-family: kruti_dev_010regular;font-size:18px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:18px;">ykbZlsUl Js.kh</option>
            <?=getDropDownPageWise('category',$row->category)?></select></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">ykbZlsUl /kkjd dk uke </span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><span style="font-family: kruti_dev_010regular;font-size:15px;"><input type="text" name="name" style="font-family: 'kruti_dev_010regular';font-size:15px;" id="name" value="<?=$name?>"></span></td>
                </tr>
				<tr>
                  
				  <td width="25%" colspan="0" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">firk@ifr dk uke</span></td>
                  <td width="25%" colspan="0" align="left" bgcolor="#E8F6FF"><span style="font-family: kruti_dev_010regular;font-size:15px;"><input type="text" name="father_name" style="font-family: 'kruti_dev_010regular';font-size:15px;" id="father_name" value="<?=$father_name?>"></span></td>
                </tr>

               <tr>
                 <td width="25%" colspan="0" align="left" bgcolor="#E8F6FF" class="newtxt1"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">Hkqxrku dk izdkj</span></strong></td>
               <td width="75%" colspan="4" align="left" bgcolor="#E8F6FF"><span style="font-family: kruti_dev_010regular;font-size:18px;">jkf&ldquo;k</span><input type="radio" onclick="javascript:loadPaymentType('CASH')" name="pay_type" id="pay_type" value="CASH" <?php if($row->pay_type=='CASH'){echo"checked";}?>> &nbsp; &nbsp;<span style="font-family: kruti_dev_010regular;font-size:18px;">psd</span>  <input type="radio" onclick="javascript:loadPaymentType('CHECK')" name="pay_type" id="pay_type" value="CHECK" <?php if($row->pay_type=='CHECK'){echo"checked";}?>> &nbsp; &nbsp;<span style="font-family: kruti_dev_010regular;font-size:18px;">MhMh</span>  <input type="radio" onclick="javascript:loadPaymentType('DD')" name="pay_type" id="pay_type" value="DD" <?php if($row->pay_type=='DD'){echo"checked";}?>>&nbsp; &nbsp;</td>
                </tr>


				<tr>
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">jkf&ldquo;k</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><input name="amount" id="amount" value="<?=$amount?>" type="text" class="newtxt"/></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">jkf&ldquo;k &#8220;kCnksa esa-</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><textarea id="amount_in_words" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="amount_in_words"><?=$amount_in_words?></textarea></td>
                </tr>

               <tr id="chk" style="<?=$chkdis?>">
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">psd uEcj</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><input name="chk_no" id="chk_no" value="<?=$chk_no?>" type="text" class="newtxt"/></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
                </tr>


				<tr id="dd" style="<?=$dddis?>">
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">MhMh uEcj</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF" ><input name="dd_no" id="dd_no" value="<?=$dd_no?>" type="text" class="newtxt"/></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
                </tr>


                <tr>
                 <td width="25%" colspan="0" align="left" bgcolor="#E8F6FF" class="newtxt1"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">tqekZuk@NwV</span></strong></td>
               <td width="75%" colspan="4" align="left" bgcolor="#E8F6FF"><span style="font-family: kruti_dev_010regular;font-size:18px;">tqekZuk</span><input type="radio" onclick="javascript:getDisType('FINE')" name="dis_type" id="dis_type" value="FINE" <?php if($row->dis_type=='FINE'){echo"checked";}?>> &nbsp; &nbsp;<span style="font-family: kruti_dev_010regular;font-size:18px;">NwV</span><input type="radio" onclick="javascript:getDisType('DIS')" name="dis_type" id="dis_type" value="DIS" <?php if($row->dis_type=='DIS'){echo"checked";}?>> &nbsp; &nbsp;</td>
                </tr>
				<tr id="fineid" style="<?=$fineiddis?>">
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">tqekZuk jkf&ldquo;k</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input name="fine_amt" id="fine_amt" value="<?=$fine_amt?>" type="text" class="newtxt"/></td>
			   <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
				</tr>
				<tr id="disid" style="<?=$disiddis?>">
			   <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">NwV  jkf&ldquo;k</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input name="dis_amt" id="dis_amt" value="<?=$dis_amt?>" type="text" class="newtxt"/></td>
			   <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
				</tr>
				<tr id="landloc"  style="<?=$landloc?>">
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">izfr&#8217;Bku</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><label>
          <select class="txtarea_id" name="location" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:18px;">izfr&#8217;Bku</option> <?=getDropDownPageWise('location',$row->location)?></select></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">izfr&#8217;Bku fuokl</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><textarea id="situate" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="situate"><?=$situate?></textarea></td>
                </tr>

				<tr>
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">fnukad</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><input name="reg_date" onblur="getExpDate(this.value)" id="reg_date" value="<?=$reg_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">fnukad</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><input name="exp_date" id="exp_date" value="<?=$exp_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
                <tr>
                  <td width="25%" colspan="1" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">fuokl</span></td>
               <td width="75%" colspan="3" align="left" bgcolor="#E8F6FF"><textarea id="address" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="address"><?=$address?></textarea></td>
				  
                </tr>
				<tr id="bt" style="<?=$btdis?>">
				  <td width="25%" colspan="1"  align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">O;olk; dk izdkj</span></td>
                  <td width="75%" colspan="3"  align="left" bgcolor="#E8F6FF"><label>
                  <textarea id="btype" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="btype"><?=$btype?></textarea></td>
                </tr>
              
                <tr id="vh1" style="<?=$vh1dis?>">
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">okgu dk izdkj</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><label>
                  <select class="txtarea_id" name="vehicle_type" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;">
                   <option value="0" style="font-family: kruti_dev_010regular;font-size:18px;"><span style="font-family: kruti_dev_010regular;font-size:18px;">okgu dk izdkj</span></option>
                   <?=getDropDownPageWise('vehicle',$row->vehicle_type)?></select></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">lhekUrxZr okgu</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><span style="font-family: kruti_dev_010regular;font-size:15px;"><textarea id="vehicle_area" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" rows="5" cols="8" name="vehicle_area"><?=$vehicle_area?></textarea></span></td>
                </tr>
				 <tr id="vh2" style="<?=$vh2dis?>">
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">okgu uEcj</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><label>
                  <input name="vehicle_no" id="vehicle_no" value="<?=$vehicle_no?>" type="text" class="newtxt"/></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">jftLVªs”ku ua0</span></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"><input name="vehicle_reg" id="vehicle_reg" value="<?=$vehicle_reg?>" type="text" class="newtxt"/></td>
                </tr>
                 
               <tr>
                  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">vafre</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><select name="final_submit" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" >
				<option value="0" style="font-family: kruti_dev_010regular;font-size:18px;">fLFkr</option>
				<option value="YES" style="font-family: kruti_dev_010regular;font-size:18px;" <?php if($row->final_submit=='YES'){echo"selected";}?>>gk</option> 
				<option value="NO" style="font-family: kruti_dev_010regular;font-size:18px;" <?php if($row->final_submit=='NO'){echo"selected";}?>>ugh</option> 
				</select></td>
				  <td width="25%" align="left" bgcolor="#E8F6FF" class="newtxt1"></td>
                  <td width="25%" align="left" bgcolor="#E8F6FF"></td>
                </tr>
              </table>
              <div>
                <div align="center"><br />
                  <input name="back" type="reset"  class="btn" style="font-family: kruti_dev_010regular;font-size:18px;" value="nqckjk" />&nbsp;&nbsp;<input style="font-family: kruti_dev_010regular;font-size:18px;" name="Save" type="submit" class="btn" value="cpk"/>
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
</form>
<SCRIPT language="JavaScript">
function loadPaymentType(getv)
{
 if(getv=='CASH')
   {
    document.getElementById('chk').style.display = 'none';
	document.getElementById('dd').style.display = 'none';

   }
   if(getv=='CHECK')
   {
    document.getElementById('dd').style.display = 'none';
	document.getElementById('chk').style.display = '';
   }
   if(getv=='DD')
   {
    document.getElementById('chk').style.display = 'none';
	document.getElementById('dd').style.display = '';

   }
}
function getExpDate(regdt)
{
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
//alert(regdt);
var getAr = regdt.split('-');
var gm = getAr[1];
var yyyy = today.getFullYear()+1;
var expdate = yyyy+'-03-31';
document.getElementById('exp_date').value=expdate;
}

function getDisType(dtype){
if(dtype=='DIS')
   {
    document.getElementById('fineid').style.display = 'none';
	document.getElementById('disid').style.display = '';

   }
if(dtype=='FINE')
   {
    document.getElementById('disid').style.display = 'none';
	document.getElementById('fineid').style.display = '';
   }


}
function loadMe(getVal)
{
if(getVal=='VEH')
{
document.getElementById('vh1').style.display = '';
document.getElementById('vh2').style.display = '';
document.getElementById('bt').style.display = 'none';
document.getElementById('landloc').style.display = 'none';

}
if(getVal=='LND')
{
document.getElementById('vh1').style.display = 'none';
document.getElementById('vh2').style.display = 'none';
document.getElementById('bt').style.display = '';
document.getElementById('landloc').style.display = '';
}
//alert(getVal);
}
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"reg_date",
			dateFormat:"%Y-%m-%d"
			}),

			new JsDatePick({
			useMode:2,
			target:"exp_date",
			dateFormat:"%Y-%m-%d"
			});
		};

var frmvalidator  = new Validator("addmrf");
//var lic_type = getRadioCheckedValue('license_type');
frmvalidator.addValidation("license_type","selone_radio","Please select license type!");
frmvalidator.addValidation("category","dontselect=0","Please select category!");
frmvalidator.addValidation("name","req","Please enter name!");
//frmvalidator.addValidation("billno","req","Please enter bill no!");
frmvalidator.addValidation("father_name","req","Please enter father's name!");
frmvalidator.addValidation("pay_type","selone_radio","Please select payment type!");
frmvalidator.addValidation("amount","req","Please enter amount!");
frmvalidator.addValidation("amount_in_words","req","Please enter amount in words!");
frmvalidator.addValidation("reg_date","req","Please enter from date!");
frmvalidator.addValidation("exp_date","req","Please enter to date!");

frmvalidator.addValidation("vehicle_type","dontselect=0","Please select vehcle type!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'VEH')");
frmvalidator.addValidation("vehicle_area","req","Please enter require info!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'VEH')");
frmvalidator.addValidation("vehicle_no","req","Please enter vehicle no!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'VEH')");
frmvalidator.addValidation("vehicle_reg","req","Please enter registration no!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'VEH')");

frmvalidator.addValidation("location","dontselect=0","Please select location!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'LND')");
frmvalidator.addValidation("situate","req","Please enter require field!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'LND')");
frmvalidator.addValidation("btype","req","Please enter type!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['license_type'],'LND')");
frmvalidator.addValidation("address","req","Please enter address!"); 
//frmvalidator.setAddnlValidationFunction("DoCustomValidationPayment"); 
frmvalidator.addValidation("chk_no","req","Please enter check no",
        "VWZ_IsChecked(document.forms['addmrf'].elements['pay_type'],'CHECK')");
frmvalidator.addValidation("dd_no","req","Please enter dd no.!",
        "VWZ_IsChecked(document.forms['addmrf'].elements['pay_type'],'DD')");

function getRadioCheckedValue(radio_name)
{

   var oRadio = document.forms[0].elements[radio_name];
 
   for(var i = 0; i < oRadio.length; i++)
   {
      if(oRadio[i].checked)
      {
         return oRadio[i].value;
      }
   }
 
   return '';
}
 </SCRIPT>
    <script type="text/javascript">

       function chkval(){
	   if(document.getElementById("renew").checked==true){
       document.getElementById("reg_date").value="";
	   document.getElementById("exp_date").value="";
	   }
	   if(document.getElementById("renew").checked == false){
		
	   }

	   }

    </script>

