<?php
$id=$_GET['id'];   
$task=$_GET['task'];
$name="";
if(!empty($id))
{
$r =$obj->showdata('addmaddata','',$id);	
$row = mysql_fetch_object($r);
$madid= $row->madid;
$ref_letno = $row->ref_letno;
$mad_amount = $row->mad_amount;
$bank_name = $row->bank_name;
$bank_acno = $row->bank_acno;
$receive_date = $row->receive_date;
$comments = $row->comments;
$total_ava_amt=$row->total_ava_amt;
$workdesc=$row->workdesc;
$mad_interest=$row->mad_interest;
}

$targetdate= date("Y-m-d",mktime(0,0,0,date('m')+1,date('d'),date('Y')));
if(!empty($_POST['Save']))
{
if(!empty($id) and $task=='edit')
$n=$obj->saveMadData($id);
else
$n=$obj->saveMadData();
if($n>0) header("location:index.php?page=madlist&msg=Data saved successfully");
}
?>
 <!--User Detail Start-->
     <form name="addmrf" method="post">
	 <input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
	 <!-- latter removed fields -->	 
        <!-- End latter removed fields -->          
		<div id="user_detail">
          <h3 style="margin:0; padding:0;">Add/Edit Mad Data</h3>
       </div>
        <!--User Detail End-->
        <div id="content_area">
          <div class="height_adj">
            <div align="left">&nbsp;&nbsp;&nbsp;
              <table width="100%" cellpadding="3" cellspacing="0" bgcolor="#efcca4" >
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">en dk uke </td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="madid" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" id="madid">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:18px;">en pqu</option> <?=getDropDownPageWise('mad',$row->madid)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">dk;Z fooj.k </td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="workdesc" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" id="workdesc">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:18px;">fooj.k pqu</option> <?=getDropDownPageWise('workdesc',$row->workdesc)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">i= la[;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="ref_letno" id="ref_letno" value="<?=$ref_letno?>" type="text" class="newtxt"/></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">en jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="mad_amount" id="mad_amount" value="<?=$mad_amount?>" type="text" class="newtxt"/></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">en lwn jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="mad_interest" id="mad_interest" value="<?=$mad_interest?>" type="text" class="newtxt"/></td>
                </tr>
               <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">cSd dk uke </td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="bank_name" id="bank_name" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" onChange="javascript:getActNo(this.value);">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:15px;">cSd pqu</option> <?=getDropDownPageWise('bank',$row->bank_name)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">[kkrk la[;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="bank_acno" id="bank_acno" readonly="true" value="<?=$bank_acno?>" type="text" class="newtxt"/></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">izkIr fnukad</td>
                  <td width="25%" align="left" bgcolor="#efcca4"><input name="receive_date" id="receive_date" value="<?=$receive_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
                <tr>
                  <td width="25%" colspan="1" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">fooj.k</td>
               <td width="75%" colspan="3" align="left" bgcolor="#efcca4"><textarea id="comments" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="comments"><?=$comments?></textarea></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;" >dqy cpk jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input readonly="true" name="total_ava_amt" id="total_ava_amt" value="<?=$total_ava_amt?>" type="text" class="newtxt"/></td>
                </tr>
              </table>
              <div>
                <div align="center"><br />
                  <input name="back" type="reset"  class="btn" style="font-family: 'kruti_dev_010regular';font-size:15px;" value="nqckjk" />&nbsp;&nbsp;<input name="Save" type="submit" style="font-family: 'kruti_dev_010regular';font-size:15px;" class="btn" value="cpk"/>
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
function getActNo(bid)
{
		//alert(foldername);
		var strURL="getacno.php?bid="+bid;
		var req = getXMLHTTP();
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {	
					   var getval = req.responseText;
					  document.getElementById('bank_acno').value=getval; 
													
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

window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"receive_date",
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
frmvalidator.addValidation("ref_letno","req","Please enter reference no.!");
frmvalidator.addValidation("mad_amount","req","Please enter mad amount!");
frmvalidator.addValidation("workdesc","dontselect=0","Please select workdesc!");
frmvalidator.addValidation("workdesc","dontselect=0","Please select work desciption!");
frmvalidator.addValidation("bank_name","dontselect=0","Please select bank!");
frmvalidator.addValidation("bank_acno","req","Please enter account no.!");
frmvalidator.addValidation("receive_date","req","Please enter receive date!");
 </SCRIPT>