<?php
$id=$_GET['id'];   
$task=$_GET['task'];
$name="";
if(!empty($id))
{
$r =$obj->showdata('interest','',$id);	
$row = mysql_fetch_object($r);
$bankid = $row->bankid;
$accountno = $row->accountno;
$interest_amt = $row->interest_amt;
$interest_date = $row->interest_date;
$totavaamt = $row->totavaamt;
}

$targetdate= date("Y-m-d",mktime(0,0,0,date('m')+1,date('d'),date('Y')));
if(!empty($_POST['Save']))
{
if(!empty($id) and $task=='edit')
$n=$obj->saveInterestData($id);
else
$n=$obj->saveInterestData();
if($n>0) header("location:index.php?page=interest&msg=Data saved successfully");
}
?>
 <!--User Detail Start-->
     <form name="addmrf" method="post">
	 <input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
	 <!-- latter removed fields -->	 
        <!-- End latter removed fields -->          
		<div id="user_detail">
          <h3 style="margin:0; padding:0;">Add/Edit Interest</h3>
       </div>
        <!--User Detail End-->
        <div id="content_area">
          <div class="height_adj">
            <div align="left">&nbsp;&nbsp;&nbsp;
              <table width="100%" cellpadding="3" cellspacing="0" bgcolor="#B3E0FF" >
				 <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">cSd dk uke </td>
               <td width="25%" align="left" bgcolor="#efcca4"><label>
               <select class="txtarea_id" name="bankid" id="bankid" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" onChange="javascript:getInterestinfo(this.value);">
               <option value="0" style="font-family: 'kruti_dev_010regular';font-size:18px;">cSd pqu</option> <?=getDropDownPageWise('bank',$row->bankid)?></select></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">[kkrk la[;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="accountno" id="accountno" readonly="true" value="<?=$accountno?>" type="text" class="newtxt"/></td>
                </tr>
                 <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">tek jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="avaamt" id="avaamt" readonly="true" value="<?=$totavaamt?>" type="text" class="newtxt"/><input name="hidtotamt" id="hidtotamt" readonly="true"  value="<?=$totavaamt?>" type="hidden"/></td>
                </tr>
				 <tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">lwn jkf&ldquo;k</td>
               <td width="25%" align="left" bgcolor="#efcca4"><input name="interest_amt" id="interest_amt" onchange="return calInterest(this.value)"  value="" type="text" class="newtxt"/></td>
                </tr>
				<tr>
                  <td width="25%" align="left" bgcolor="#efcca4" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">fnukad</td>
                  <td width="25%" align="left" bgcolor="#efcca4"><input name="interest_date" id="interest_date" value="<?=$interest_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
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
function getInterestinfo(bid)
{
		//alert(foldername);
		var strURL="getinterestinfo.php?bid="+bid;
		var req = getXMLHTTP();
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {	
                      var getval = req.responseText;
					  getArray = getval.split("#");
					  document.getElementById('accountno').value=getArray[0]; 
					  document.getElementById('avaamt').value=getArray[1]; 
					  document.getElementById('hidtotamt').value=getArray[1]; 
													
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
function calInterest(inval)
{
// var gettotval=0;
 document.getElementById('avaamt').value = document.getElementById('hidtotamt').value
 document.getElementById('avaamt').value= parseInt(document.getElementById('avaamt').value) + parseInt(inval);
}
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"interest_date",
			dateFormat:"%Y-%m-%d"
			}),

			new JsDatePick({
			useMode:2,
			target:"updated_date",
			dateFormat:"%Y-%m-%d"
			});
		};
var frmvalidator  = new Validator("addmrf");
frmvalidator.addValidation("bankid","dontselect=0","Please select bank!");
frmvalidator.addValidation("interest_amt","req","Please enter interest amount.!");
frmvalidator.addValidation("interest_date","req","Please enter receive date!");
 </SCRIPT>