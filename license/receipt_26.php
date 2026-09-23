<?php
ini_set('display_errors', 'Off');
error_reporting(0);
include_once('class.hrrecruitment.php');
include_once('number2word.php');
$obj = new recruitment();
$res=$obj->print_doc($_GET['id']);
$pbtn="";
if($_GET['pb']){$pbtn=$_GET['pb'];}
$row=mysql_fetch_object($res);
//$level =;
$name =$row->name;
$father_name = $row->father_name;
// end salary calculation
$license_no=$row->license_no;
$category=$obj->getMasterName('category','name',$row->category);
$location=$obj->getMasterName('location','name',$row->location);
$amount=$row->amount;
$amount_in_words=$row->amount_in_words;
$situate=$row->situate;
$btype=$row->btype;
$address=$row->address;
$mobno=$row->mobno;
$updated_date=strftime("%d-%m-%Y", strtotime($row->updated_date));
$reg_date= strftime("%d-%m-%Y", strtotime($row->reg_date));
$exp_date=strftime("%d-%m-%Y", strtotime($row->exp_date));
$reg_by=$row->reg_by;
//$billno = $row->billno;
$vehicle_type = $obj->getMasterName('vehicle','name',$row->vehicle_type);
$vehicle_area = $row->vehicle_area;
$vehicle_no   = $row->vehicle_no;
$vehicle_reg  = $row->vehicle_reg;
$dis_type= $row->dis_type;
$fine_amt= $row->fine_amt;
$dis_amt= $row->dis_amt;
$chalanno= "JNNLD-".$row->id;
$curdate =date('d-m-Y');
$dis_type_display="";
$total_amount = $amount;
if($dis_type=='DIS')
{
$dis_type_display = $dis_amt;
$total_amount = $amount-$dis_amt;
}
if($dis_type=='FINE')
{
$dis_type_display = $fine_amt;
$total_amount = $amount+$fine_amt;
}
$pay_type= $row->pay_type;
$chk_no= $row->chk_no;
$dd_no= $row->dd_no;
if($chk_no){$ckddno=$chk_no;}
if($dd_no){$ckddno=$dd_no;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Receipt</title>
<link href="form.css" rel="stylesheet" type="text/css" />
<link href="css/stylesheet.css" type="text/css" rel="stylesheet" />

<script language="javascript">
function printPage()
{
//document.getElementById('headerlogo').style.display = 'none';
document.getElementById('pprint').style.display = 'none';
document.getElementById('pclose').style.display = 'none';
document.getElementById('pbutton').style.display = 'none';
window.print()
self.close();
}
</script>
</head>
<body>
<div style="float:float; width:100%;  position:absolute; left:0%; top:50%; margin-top:-150px; z-index:9; text-align:center;"><img src="images/logo_bg.jpg" alt="" style="width:50%"/></div>

<div class="wrapper" style="position:relative; z-index:999">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="receipt">
  <tr>
  <td colspan="4"><h2 class="left" style="font-family:'kruti_dev_010regular';">gfjr >k¡lh</h2></td><td nowrap><h2 class="right" style="font-family:'kruti_dev_010regular';">LoPN >k¡lh</h2></td>
  </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" class="rlogo"><img style="display:none" width="71" height="67" src="images/logo.png"></td>
      <td valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="dateid">
          <tr>
            <td nowrap="true">Unique ID NO.: <strong><?php echo $license_no;?></strong></td>
          </tr>
          <tr>
            <td>Date:<strong><?php echo $curdate;?></strong></td>
          </tr>
        </table></td>
    </tr>
	<tr>
      <td colspan="4"><h2 style="font-family:'kruti_dev_010regular';"><strong>>k¡lh uxj fuxe</strong></h2></td>
    </tr>
    <tr>
      <td colspan="4"><h2>License Department Receipt</h2></td>
    </tr>
    <tr>
      <td>Receipt No:</td>
      <td><strong><?php echo $chalanno;?></strong></td>
    </tr>
    <tr>
      <td>Name:</td>
      <td style="font-family:'kruti_dev_010regular';"><strong><?php echo $name;?></strong></td>
    </tr>
    <tr>
      <td>Address:</td>
      <td style="font-family:'kruti_dev_010regular';"><strong><?php echo $address;?></strong></td>
     
    </tr>
	<?php if($dis_type){?>
	<tr>
      <?php if($dis_type=='DIS'){ ?><td>Discount Amount-</td><?php } 
	  else {?><td >Intrest on Due Amount-</td><?php }?>
      <td colspan="2"><strong><?php echo $dis_type_display;?></strong></td>
	  <td >&nbsp;</td>
      <td>&nbsp;</td><FRAMESET ROWS="," COLS=",">
		<FRAME SRC="" NAME="">
		<FRAME SRC="" NAME="">
      </FRAMESET>
    </tr>
	<?php }?>

    <tr>
      <td>Chaque/DD/Cash.</td>
      <td><strong><?php echo $pay_type;?></strong></td>
      <td>DD No.</td>
      <td><strong><?php echo $ckddno;?></strong></td>
    </tr>
    <tr>
      <td>Bank's Name:</td>
      <td></td>
	  <td>Current Amount:</td>
      <td><strong><?php echo $amount;?></strong></td>
    </tr>
    <tr>
	
      <td>Chaque No.:</td>
      <td><strong><?php echo $ckddno;?></strong></td>
      <td>Total Rs.</td>
      <td><strong><?php echo $total_amount;?></strong></td>
    </tr>
    <tr>
   
      <td colspan="4" class="bbor">&nbsp;</td>

    </tr>
    <tr>
   
      <td colspan="4" class="txt">टिपप्णी :  यह   कम्यूटर जनरेटेड रसीद है य़दि कोई त्रुटि हो तो मुख्य कर निर्धारण अधिकारी से सम्पक करे I चेक द्वारा जमा करने पर चेक बैंक् से  क्लीयरेन्स  होने पर ही  भुगतान  मान्य होगा । </td>
    </tr>
    <tr>
   
      <td colspan="4" class="rtex" align="right" width="100%">Signature of Cashier</td><td>&nbsp;</td>

    </tr>
	<tr>
	<td colspan="3">
<p align="center" id="pbutton"><input style="background:#000066; color:#FFFFFF; border:none; padding:5px; font-size:11px;" type="button" value="Print" name="pprint" id="pprint" onClick="printPage()"><!--&nbsp;&nbsp;<input style="background:#000066; color:#FFFFFF; border:none; font-size:11px;" type="button" value="Normal print" name="pprint1" onClick="printPage1()">-->&nbsp;<input style="background:#000066; color:#FFFFFF; padding:5px; border:none; font-size:11px;" type="button" value="Close" name="pclose" id="pclose" onClick="self.close();"></p>	</td>
	</tr>
  </table>
</div>
</body>
</html>
