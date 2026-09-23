<?php
$id=$_GET['id'];   
$task=$_GET['task'];
$name="";
if(!empty($id))
{
$r =$obj->showdata('payamount','',$id);	
$row = mysql_fetch_object($r);
$madid= $row->madid;
$mad_amount = $row->mad_amount;
$contractorid = $row->contractorid;
$bankid = $row->bankid;
$checkno = $row->checkno;
$paidamt = $row->paidamt;
$ava_amt = $row->ava_amt;
$payment_date=$row->payment_date;
$workdesc=$row->workdesc;
$totavaAmt = $obj->getTotAvaamt($workdesc,$madid);
}

$targetdate= date("Y-m-d",mktime(0,0,0,date('m')+1,date('d'),date('Y')));
if(!empty($_POST['Save']))
{
if(!empty($id) and $task=='edit')
$n=$obj->savePayData($id);
else
$n=$obj->savePayData();
if($n>0) header("location:index.php?page=payamtlist&msg=Data saved successfully");
}
?>
 <!--User Detail Start-->
     <form name="addmrf" method="post">
	 <input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
	 <!-- latter removed fields -->	 
        <!-- End latter removed fields -->          
		<div id="user_detail">
          <h3 style="margin:0; padding:0;">Add/Edit Payment</h3>
       </div>
        <!--User Detail End-->
        <div id="content_area">
          <div class="height_adj">
            <div align="left">&nbsp;&nbsp;&nbsp;
              <table width="100%" cellpadding="3" cellspacing="0" bgcolor="#efcca4" >
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">en dk uke</td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="madid" id="madid" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" >
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:15px;">en pqu</option> <?=getDropDownPageWise('mad',$row->madid)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">dk;Z fooj.k </td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="workdesc" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" id="workdesc" onchange="getAvlAmt(this.value)">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:18px;">dk;Z fooj.k pqu</option> <?=getDropDownPageWise('workdesc',$row->workdesc)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">cpk jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="ava_amt" readonly="true" id="ava_amt" value="<?=$totavaAmt?>" type="text" class="newtxt"/><input name="hidava_amt" readonly="true" id="hidava_amt" value="" type="hidden" class="newtxt"/></td>
                </tr>
				 <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">dEiuh dk uke</td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="contractorid" id="contractorid" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:18px;">dEiuh dk uke pqu</option> <?=getDropDownPageWise('contractor',$row->contractorid)?></select></td>
                </tr>
				 <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">cSd dk uke</td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="bankid" id="bankid" style="font-family: 'kruti_dev_010regular';font-size:18px;width:130px;">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:15px;">cSd dk uke</option> <?=getDropDownPageWise('bank',$row->bankid)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">psad uEcj</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="checkno" id="checkno" value="<?=$checkno?>" type="text" class="newtxt"/></td>
                </tr>
                 <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">Hkqxrku jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="paidamt" id="paidamt"  value="<?=$paidamt?>" onchange="calAvalAmt(this.value);" type="text" class="newtxt"/></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">Hkqxrku fnukad</td>
                  <td width="25%" align="left" bgcolor="#efcca4"><input name="payment_date" id="payment_date" value="<?=$payment_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
              </table>
              <div>
                <div align="center"><br />
                  <input name="back" type="reset"  class="btn" value="nqckjk"style="font-family: 'kruti_dev_010regular';font-size:15px;" />&nbsp;&nbsp;<input name="Save" type="submit" class="btn" style="font-family: 'kruti_dev_010regular';font-size:15px;" value="cpk"/>
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
</form>
<SCRIPT language="JavaScript">
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
function getAvlAmt(workdesc)
{
		var mid = document.getElementById('madid').value;
		var strURL="gettotavlamt.php?mid="+mid +"&workdesc="+workdesc;
		//alert(strURL);
		var req = getXMLHTTP();
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {	
					   var getval = req.responseText;
					  document.getElementById('ava_amt').value=getval; 
					  document.getElementById('hidava_amt').value=getval; 

													
					} 
					
					else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}
			  				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}

}
//function to calculate add interest
function calAvalAmt(inval)
{
document.getElementById('ava_amt').value = document.getElementById('hidava_amt').value; 
document.getElementById('ava_amt').value= parseInt(document.getElementById('ava_amt').value) - parseInt(inval);
}

window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"payment_date",
			dateFormat:"%Y-%m-%d"
			}),

			new JsDatePick({
			useMode:2,
			target:"updated_date",
			dateFormat:"%Y-%m-%d"
			});
		};
var frmvalidator  = new Validator("addmrf");
frmvalidator.addValidation("madid","dontselect=0","Please select Mad!");
frmvalidator.addValidation("workdesc","dontselect=0","Please select work desciption!");
frmvalidator.addValidation("contractorid","dontselect=0","Please select work contractor!");
frmvalidator.addValidation("bankid","dontselect=0","Please select bank!");
frmvalidator.addValidation("checkno","req","Please enter echeck no.!");
frmvalidator.addValidation("paidamt","req","Please enter amount.!");
frmvalidator.addValidation("payment_date","req","Please enter date!");
 </SCRIPT>