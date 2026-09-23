<?php 
session_start();	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");
include("phplib/thumbclass.php");
//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


if(isset($_POST['submit']) && $_POST['submit']!=''){
  
  //print_r($_POST);
  extract($_POST);
  $rdo_Registrationtype=$rdo_Registrationtype;
  $panjikaranyear=$panjikaranyear;
  $sql="insert into contractors set Registrationtype='".addslashes($rdo_Registrationtype)."',panjikaranyear='".addslashes($panjikaranyear)."',ContractorName='".addslashes($ContractorName)."',pata='".addslashes($pata)."',Phone='".addslashes($Phone)."',Email='".addslashes($Email)."',rastriyata='".addslashes($rastriyata)."',jati='".addslashes($jati)."',dharm='".addslashes($dharm)."',Isshapathpatra='".addslashes($rdo_shapathpatra)."',propwriter='".addslashes($propwriter)."',propwritersthaipata='".addslashes($propwritersthaipata)."',propwriterpatrapata='".addslashes($propwriterpatrapata)."',propwriterpartner='".addslashes($propwriterpartner)."',Ishashiyatpramanpatra='".addslashes($rdo_hashiyatpramanpatra)."',hashiyatkirashi='".addslashes($hashiyatkirashi)."',hashiyatkishreni='".addslashes($hashiyatkishreni)."',Isnewcharitrapramanpatra='".addslashes($rdo_newcharitrapramanpatra)."',howoldnewcharitrapramanpatra='".addslashes($rdo_howoldnewcharitrapramanpatra)."',Isitccirtificate='".addslashes($rdo_itccirtificate)."',pancardno='".addslashes($pancardno)."',aaykarreturnrashi='".addslashes($aaykarreturnrashi)."',aaykarreturndate='".addslashes($aaykarreturndate)."',Isfirmristedar='".addslashes($rdo_firmristedar)."',ristedarname='".addslashes($ristedarname)."',ristedarpadname='".addslashes($ristedarpadname)."',ristedarpata='".addslashes($ristedarpata)."',Isfirmagainstkarywahi='".addslashes($rdo_firmagainstkarywahi)."',kalisuchifirmname='".addslashes($kalisuchifirmname)."',kalisuchifirmvibhag='".addslashes($kalisuchifirmvibhag)."',kalisuchikaran='".addslashes($kalisuchikaran)."',dibarfirmname='".addslashes($dibarfirmname)."',dibarfirmvibhag='".addslashes($dibarfirmvibhag)."',dibarkaran='".addslashes($dibarkaran)."',arthdandfirmname='".addslashes($arthdandfirmname)."',arthdandfirmvibhag='".addslashes($arthdandfirmvibhag)."',arthdandkaran='".addslashes($arthdandkaran)."',firmEngName1='".addslashes($firmEngName1)."',firmEngPata1='".addslashes($firmEngPata1)."',firmEngPhone1='".addslashes($firmEngPhone1)."',firmEngEmail1='".addslashes($firmEngEmail1)."',firmEngName2='".addslashes($firmEngName2)."',firmEngPata2='".addslashes($firmEngPata2)."',firmEngPhone2='".addslashes($firmEngPhone2)."',firmEngEmail2='".addslashes($firmEngEmail2)."',Isfu_stoff='".addslashes($rdo_fu_stoff)."',sahayakno='".addslashes($sahayakno)."',ourno='".addslashes($ourno)."',supervisorno='".addslashes($supervisorno)."',metno='".addslashes($metno)."',anyano='".addslashes($anyano)."',Isvikashkarya='".addslashes($rdo_vikashkarya)."',sarkarivibhagvivaran='".addslashes($sarkarivibhagvivaran)."',sarkarivibhagrashi='".addslashes($sarkarivibhagrashi)."',manyatasansthavivaran='".addslashes($manyatasansthavivaran)."',manyatasanstharashi='".addslashes($manyatasanstharashi)."',Isanubhavpramarpatra='".addslashes($rdo_anubhavpramarpatra)."',Ismafiyashapathpatra='".addslashes($rdo_mafiyashapathpatra)."',mukadma='".addslashes($mukadma)."',dhara='".addslashes($dhara)."',thana='".addslashes($thana)."',janpad='".addslashes($janpad)."',nyayalay='".addslashes($nyayalay)."',sajavivaran='".addslashes($sajavivaran)."',sajaavadhi='".addslashes($sajaavadhi)."',Ispanjikaranrashid='".addslashes($rdo_panjikaranrashid)."',panjikaranrashid='".addslashes($panjikaranrashid)."',photo='".addslashes($photo)."',signature='".addslashes($signature)."',name='".addslashes($name)."',address='".addslashes($address)."',date='".addslashes($date)."',AddedDate='".date('yy-mm-dd')."',ModifiedDate='".date('yy-mm-dd')."',Status='Inactive'";
  $result=$db->query($sql);
  
  
  $ContractorId=$db->insert_id($result);
  		  
	
  if($rdo_shapathpatra=='yes'){
     $shapathpatra=$_FILES['shapathpatra']['name'];
	 if($shapathpatra!=''){
       $shapathpatra=$ContractorId.'_'.$shapathpatra;
	   $uploadpath=GetUploadPath('sapathpatra/'.$shapathpatra);
	   
	   if(move_uploaded_file($_FILES['shapathpatra']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadPath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $shapathpatra='';
	 }
	 $sql_shapathpatra="update contractors set shapathpatra='$shapathpatra',Isshapathpatra='$rdo_shapathpatra' where ContractorId=$ContractorId";
	 $db->query($sql_shapathpatra);

  }

  if($rdo_hashiyatpramanpatra=='yes'){
     $hashiyatpramanpatra=$_FILES['hashiyatpramanpatra']['name'];
	 if($hashiyatpramanpatra!=''){
       $hashiyatpramanpatra=$ContractorId.'_'.$hashiyatpramanpatra;
	   $uploadpath=GetUploadPath('hashiyatpramanpatra/'.$hashiyatpramanpatra);
	   
	   if(move_uploaded_file ($_FILES['hashiyatpramanpatra']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $hashiyatpramanpatra='';
	 }
	 $sql_hashiyatpramanpatra="update contractors set hashiyatpramanpatra='$hashiyatpramanpatra',Ishashiyatpramanpatra='$rdo_hashiyatpramanpatra' where ContractorId=$ContractorId";
	 $db->query($sql_hashiyatpramanpatra);

  }

  if($rdo_newcharitrapramanpatra=='yes'){
     $newcharitrapramanpatra=$_FILES['newcharitrapramanpatra']['name'];
	 if($newcharitrapramanpatra!=''){
       $newcharitrapramanpatra=$ContractorId.'_'.$newcharitrapramanpatra;
	   $uploadpath=Getuploadpath('newcharitrapramanpatra/'.$newcharitrapramanpatra);
	   
	   if(move_uploaded_file ($_FILES['newcharitrapramanpatra']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $newcharitrapramanpatra='';
	 }
	 $sql_newcharitrapramanpatra="update contractors set newcharitrapramanpatra='$newcharitrapramanpatra',Isnewcharitrapramanpatra='$rdo_newcharitrapramanpatra' where ContractorId=$ContractorId";
	 $db->query($sql_newcharitrapramanpatra);

  }

  if($rdo_itccirtificate=='yes'){
     $itccirtificate=$_FILES['itccirtificate']['name'];
	 if($itccirtificate!=''){
       $itccirtificate=$ContractorId.'_'.$itccirtificate;
	   $uploadpath=Getuploadpath('itccirtificate/'.$itccirtificate);
	   
	   if(move_uploaded_file ($_FILES['itccirtificate']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $itccirtificate='';
	 }
	 $sql_itccirtificate="update contractors set itccirtificate='$itccirtificate',Isitccirtificate='$rdo_itccirtificate' where ContractorId=$ContractorId";
	 $db->query($sql_itccirtificate);

  }

  if($rdo_firmristedar=='yes'){
     $firmristedar=$_FILES['firmristedar']['name'];
	 if($firmristedar!=''){
       $firmristedar=$ContractorId.'_'.$firmristedar;
	   $uploadpath=Getuploadpath('firmristedar/'.$firmristedar);
	   
	   if(move_uploaded_file ($_FILES['firmristedar']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $firmristedar='';
	 }
	 $sql_firmristedar="update contractors set firmristedar='$firmristedar',Isfirmristedar='$rdo_firmristedar' where ContractorId=$ContractorId";
	 $db->query($sql_firmristedar);

  }

  if($rdo_firmagainstkarywahi=='yes'){
     $firmagainstkarywahi=$_FILES['firmagainstkarywahi']['name'];
	 if($firmagainstkarywahi!=''){
       $firmagainstkarywahi=$ContractorId.'_'.$firmagainstkarywahi;
	   $uploadpath=Getuploadpath('firmagainstkarywahi/'.$firmagainstkarywahi);
	   
	   if(move_uploaded_file ($_FILES['firmagainstkarywahi']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $firmagainstkarywahi='';
	 }
	 $sql_firmagainstkarywahi="update contractors set firmagainstkarywahi='$firmagainstkarywahi',Isfirmagainstkarywahi='$rdo_firmagainstkarywahi' where ContractorId=$ContractorId";
	 $db->query($sql_firmagainstkarywahi);

  }


  if($rdo_fu_stoff=='yes'){
     $fu_stoff=$_FILES['fu_stoff']['name'];
	 if($fu_stoff!=''){
       $fu_stoff=$ContractorId.'_'.$fu_stoff;
	   $uploadpath=Getuploadpath('fu_stoff/'.$fu_stoff);
	   
	   if(move_uploaded_file ($_FILES['fu_stoff']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $fu_stoff='';
	 }
	 $sql_fu_stoff="update contractors set fu_stoff='$fu_stoff',Isfu_stoff='$rdo_fu_stoff' where ContractorId=$ContractorId";
	 $db->query($sql_fu_stoff);

  }

  if($rdo_vikashkarya=='yes'){
     $vikashkarya=$_FILES['vikashkarya']['name'];
	 if($vikashkarya!=''){
       $vikashkarya=$ContractorId.'_'.$vikashkarya;
	   $uploadpath=Getuploadpath('vikashkarya/'.$vikashkarya);
	   
	   if(move_uploaded_file ($_FILES['vikashkarya']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $vikashkarya='';
	 }
	 $sql_vikashkarya="update contractors set vikashkarya='$vikashkarya',Isvikashkarya='$rdo_vikashkarya' where ContractorId=$ContractorId";
	 $db->query($sql_vikashkarya);

  }

  if($rdo_anubhavpramarpatra=='yes'){
     $anubhavpramarpatra=$_FILES['anubhavpramarpatra']['name'];
	 if($anubhavpramarpatra!=''){
       $anubhavpramarpatra=$ContractorId.'_'.$anubhavpramarpatra;
	   $uploadpath=Getuploadpath('anubhavpramarpatra/'.$anubhavpramarpatra);
	   
	   if(move_uploaded_file ($_FILES['anubhavpramarpatra']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $anubhavpramarpatra='';
	 }
	 $sql_anubhavpramarpatra="update contractors set anubhavpramarpatra='$anubhavpramarpatra',Isanubhavpramarpatra='$rdo_anubhavpramarpatra' where ContractorId=$ContractorId";
	 $db->query($sql_anubhavpramarpatra);

  }

  if($rdo_mafiyashapathpatra=='yes'){
     $mafiyashapathpatra=$_FILES['mafiyashapathpatra']['name'];
	 if($mafiyashapathpatra!=''){
       $mafiyashapathpatra=$ContractorId.'_'.$mafiyashapathpatra;
	   $uploadpath=Getuploadpath('mafiyashapathpatra/'.$mafiyashapathpatra);
	   
	   if(move_uploaded_file ($_FILES['mafiyashapathpatra']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $mafiyashapathpatra='';
	 }
	 $sql_mafiyashapathpatra="update contractors set mafiyashapathpatra='$mafiyashapathpatra',Ismafiyashapathpatra='$rdo_mafiyashapathpatra' where ContractorId=$ContractorId";
	 $db->query($sql_mafiyashapathpatra);

  }

  if($rdo_panjikaranrashid=='yes'){
     $panjikaranrashid=$_FILES['panjikaranrashid']['name'];
	 if($panjikaranrashid!=''){
       $panjikaranrashid=$ContractorId.'_'.$panjikaranrashid;
	   $uploadpath=Getuploadpath('panjikaranrashid/'.$panjikaranrashid);
	   
	   if(move_uploaded_file ($_FILES['panjikaranrashid']['tmp_name'],$uploadpath))
		   {
			 	chmod("$uploadpath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
	 }else{
	   $panjikaranrashid='';
	 }
	 $sql_panjikaranrashid="update contractors set panjikaranrashid='$panjikaranrashid',Ispanjikaranrashid='$rdo_panjikaranrashid' where ContractorId=$ContractorId";
	 $db->query($sql_panjikaranrashid);

  }

  if(is_uploaded_file($_FILES['photo']['tmp_name']))
  {
       $photo=$ContractorId.'_'.$_FILES['photo']['name'];
	   $uploadpath=Getuploadpath('photo/'.$photo);
	   $thumbDirectory=Getuploadpath('photo/thumbs/'.$photo);
	   
	   if(move_uploaded_file ($_FILES['photo']['tmp_name'],$uploadpath))
	   {
			 	chmod("$uploadpath",0777);
	   }
	   else
	   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
	   }

       //image should be a file path to the image you just uploaded, and moved.
	   $target_path=$uploadpath;
	   $image = $target_path;
	   $newImageName=$photo;
	   $thumb = new SimpleImage();
	   $thumb->load($image);
	   $width = 132;
	   $height = 132;
	   $thumb->resize($width,$height);
	   $thumb->save($thumbDirectory . $newImageName); 

	   $sql_photo="update contractors set photo='$photo' where ContractorId=$ContractorId";
	   $db->query($sql_photo);
  }

  if(is_uploaded_file($_FILES['signature']['tmp_name']))
  {
       $signature=$ContractorId.'_'.$signature;
	   $uploadpath=Getuploadpath('signature/'.$signature);
	   $thumbDirectory=Getuploadpath('signature/thumbs/'.$signature);

	   if(move_uploaded_file ($_FILES['signature']['tmp_name'],$uploadpath))
	   {
			 	chmod("$uploadpath",0777);
	   }
	   else
	   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
	   }

	   $sql_photo="update contractors set signature='$signature' where ContractorId=$ContractorId";
	   $db->query($sql_photo);
  
         $signature=$_FILES['signature']['name'];
	     //image should be a file path to the image you just uploaded, and moved.
		 $target_path=$uploadpath;
		 $image = $target_path;
		 $newImageName=$signature;
		 $thumb = new SimpleImage();
		 $thumb->load($image);
		 $width = 132;
		 $height = 75;
		 $thumb->resize($width,$height);
		 $thumb->save($thumbDirectory . $newImageName); 
  }




}


$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/registration.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function Getuploadpath($filepath){
  
  global $DOCUMENT_ROOT;

  if($_SERVER['SERVER_NAME']=='localhost')
  {
	$uploadpath=$DOCUMENT_ROOT.'contractors/'.$filepath;
  }
  else
  {
	$uploadpath=$DOCUMENT_ROOT.'/'.$filepath;
  }

  return $uploadpath;
}
?>
