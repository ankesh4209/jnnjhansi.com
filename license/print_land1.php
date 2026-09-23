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
$reg_date= strftime("%d-%m-%Y", strtotime('2015-04-01'));
$exp_date=strftime("%d-%m-%Y", strtotime('2016-03-31'));
$reg_by=$row->reg_by;
//$billno = $row->billno;
$dis_type= $row->dis_type;
$dis_type_display="";
if($dis_type=='DIS')
{
$dis_type_display = "-".$dis_amt;
}
if($dis_type=='FINE')
{
$dis_type_display = "-".$dis_amt;
}
$fine_amt= $row->fine_amt;
$dis_amt= $row->dis_amt;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Form</title>
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

<link href="form.css" type="text/css" rel="stylesheet" media="all" />
<link href="css/stylesheet.css" type="text/css" rel="stylesheet" />

</head>
<div style="float:float; width:100%; position:absolute; left:0%; top:50%; margin-top:-200px; z-index:9; text-align:center;"><img src="images/logo_bg.jpg" alt="" /></div>
<body>
<div class="wrapper" style="position:relative; z-index:999;">
  <div class="header"><img style="display:none" width="71" height="67" src="images/logo.png"><span class="left" style="font-family:'kruti_dev_010regular';">gfjr >k¡lh</span><span class="right" style="font-family:'kruti_dev_010regular';">LoPN >k¡lh</span> </div>
  <div class="licence">
    <h5 style="font-family:'kruti_dev_010regular';">izi= la[;k&12</h5>
    <h4 style="font-family:'kruti_dev_010regular';">>k¡lh uxj fuxe] >k¡lh<br />
      <u style="font-family:'kruti_dev_010regular';">¼ykbZlsUl½</u></h4>
    <ul>
      <li style="font-family:'kruti_dev_010regular';">ykbZlsUl la[;k
        <input name="" style="width:120px;font-weight:bold;font-size:17px;" type="text" value="<?php echo $license_no;?>" readonly="true" />
        <span class="right"> fnukad
        <input name="" style="width:100px;font-weight:bold;font-size:17px;" type="text" value="<?php echo $updated_date;?>" readonly="true" />
        </span> </li>
      <li style="font-family:'kruti_dev_010regular';">ykbZlsUl Js.kh
        <input name="" style="font-family: 'kruti_dev_010regular';font-size:17px;width:120px;font-weight:bold;" type="text" value="<?php echo $category;?>" readonly="true" />
      </li>
      <li style="font-family:'kruti_dev_010regular';">pwfda izkFkhZ us uxj fuxe >k¡lh dks :0
        <input name="" type="text" style="width:45px;font-weight:bold;" value="<?php echo $amount;?>" readonly="true"/>
        @& “kCnksa esa-
        <input name="" type="text" style="font-family: 'kruti_dev_010regular';font-size:17px;width:260px;font-weight:bold;" value="<?php echo $amount_in_words;?>" readonly="true"/>
      </li>
     <!-- <li style="font-family:'kruti_dev_010regular';">
        ek= jlhn uEcj
        <input name="" type="text" style="width:70px;" value="<?php echo $license_no;?>" readonly="true"/>-->
        <li style="font-family:'kruti_dev_010regular';">dk Hkqxrku fd;k gS</li>
      <li style="font-family:'kruti_dev_010regular';">budks uxj fuxe >k¡lh lhekUrxZr izfr’Bku
        <input name="" type="text" style="font-family: 'kruti_dev_010regular';font-size:17px;font-weight:bold;width:320px;" value="<?php echo $btype;?>" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">fLFkr
        <input name="" type="text" style="font-family: 'kruti_dev_010regular';font-size:17px;width:580px;font-weight:bold;" value="<?php echo $situate;?>" readonly="true"/>
        fnukad
        <input name="" type="text" style="width:100px;font-weight:bold;font-size:17px;" value="<?php echo $reg_date;?>" readonly="true"/>
        ls fnukad
        <input name="" type="text" style="width:100px;font-weight:bold;font-size:17px;" value="<?php echo $exp_date;?>" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">rd dh vuqefr nh tkrh gSA </li>
    </ul>
    <h4 style="font-family:'kruti_dev_010regular';"><u>ykbZlsUl dk o.kZu</u></h4>
    <ul>
      <li style="font-family:'kruti_dev_010regular';">ykbZlsUl /kkjd dk uke Jh@Jherh@dq0
        <input name="" style="font-family: 'kruti_dev_010regular';font-size:17px;width:250px;font-weight:bold;" type="text" value="<?php echo $name;?>" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">firk@ifr dk uke-
        <input name="" style="font-family: 'kruti_dev_010regular';font-size:17px;width:250px;font-weight:bold;" type="text" value="<?php echo $father_name;?>" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">fuokl
        <input name="" type="text" style="font-family: 'kruti_dev_010regular';font-size:17px;width:500px;font-weight:bold;" value="<?php echo $address;?>" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">
        <input name="" type="text" style="width:235px;" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">O;olk; dk izdkj
        <input name="" type="text" style="font-family: 'kruti_dev_010regular';font-size:17px;font-weight:bold;width:300px" value="<?php echo $location;?>" readonly="true"/>
      </li>
      <li style="font-family:'kruti_dev_010regular';">fVIi.kh&</li>
      <li style="font-family:'kruti_dev_010regular';">1-	LFky vkSj i`’Bkadu dh izfof’V rd gh dh tkuh pkfg;sa tc ykbZlsUl lizfrcU/k gks] tc ykbZlsUl lkekU; izd`fr dk gks rks i`’Bkadu jn~ dj fn;k tkuk pkfg;saA</li>
      <li style="font-family:'kruti_dev_010regular';">2-	bl ykbZlsUl dh Lohd`fr fu;eksa vkSj “krksZ ds v/khu jgrs gq;s uxj fuxe vf/kfu;e ds fofu;eu vkSj fu;a=.k ds fy;s dh tkrh gSA ftldh ,d izfr vkt esjs }kjk ykbZlsUl/kkjh dks nh x;h gSA</li>
      <li style="font-family:'kruti_dev_010regular';">ykbZlsUl tkjh djus dk fnukad %& <strong><?php echo $updated_date;?></strong></li>
    </ul><br>
    <h3 style="font-family:'kruti_dev_010regular';padding-top:120px;">ykbZlsUl vf/kdkjh<br />
      >k¡lh uxj fuxe >k¡lh </h3>
  </div>
  <?php if($pbtn=='vi') {?>
<p align="center" id="pbutton"><input style="background:#000066; color:#FFFFFF; border:none; padding:5px; font-size:11px;" type="button" value="Print" name="pprint" id="pprint" onClick="printPage()"><!--&nbsp;&nbsp;<input style="background:#000066; color:#FFFFFF; border:none; font-size:11px;" type="button" value="Normal print" name="pprint1" onClick="printPage1()">-->&nbsp;<input style="background:#000066; color:#FFFFFF; padding:5px; border:none; font-size:11px;" type="button" id="pclose" value="Close" name="pclose" onClick="self.close();"></p><?php }?>
</div>
</body>
</html>
