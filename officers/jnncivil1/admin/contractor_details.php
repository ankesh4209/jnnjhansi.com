<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: ../login.php"); 				
	  exit;
  }
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$cid = $_REQUEST['cid'];
if($_POST['BackUrl']==''){
	$BackUrl= $_SERVER['HTTP_REFERER'];
}else{
	$BackUrl= $_POST['BackUrl'];
}

if($_POST["submit"] && $_POST["submit"]=='Update Status')
{	
 		ChangeStatus( $db,$cid);
	
}



if(isset($_GET['msg']) && $_GET['msg']=='succ')
{
	$PROMPT='Contractors status has been updated.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Contractors status has been updated.';
}
viewpage($db,$db1,$cid);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/contractor_details.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$cid)
 {
   global $cid,$ContractorName,$Email,$Phone,$password,$rastriyata,$jati,$dharm,$Isshapathpatra1,$shapathpatraLink,$strsapthpatra,$Status,$Approved,$Cancelled,$Pending,$propwriter,$propwritersthaipata,$propwriterpatrapata,$propwriterpartner,$Ishashiyatpramanpatra1,$hashiyatkirashi,$hashiyatkishreni,$hashiyatpramanpatraLink,$strhashiyatpramanpatra,$Isnewcharitrapramanpatra1,$newcharitrapramanpatra,$Ishowoldnewcharitrapramanpatra,$howoldnewcharitrapramanpatra,$howoldnewcharitrapramanpatraLink,
	   $strhowoldnewcharitrapramanpatra,$itccirtificate,$Isitccirtificate1,$stritccirtificate,$aaykarreturndate,$pancardno,$aaykarreturnrashi,$Isfirmristedar1,$firmristedar,$strfirmristedar,$ristedarpata,$ristedarpadname,$ristedarname,$kalisuchifirmname,$kalisuchifirmvibhag,$kalisuchikaran,$dibarfirmname,$dibarfirmvibhag,$dibarkaran,$dibarfirm,$arthdandfirmname, $arthdandfirmvibhag, $arthdandkaran, $firmEngName1, $firmEngPata1, $firmEngPhone1, $firmEngEmail1, $firmEngName2, $firmEngPata2, $firmEngPhone2, $firmEngEmail2, $Isfu_stoff, $fu_stoff, $sahayakno, $ourno, $supervisorno, $metno, $anyano, $Isvikashkarya, $vikashkarya, $sarkarivibhagvivaran, $sarkarivibhagrashi, $manyatasansthavivaran, $manyatasanstharashi, $Isanubhavpramarpatra, $anubhavpramarpatra, $Ismafiyashapathpatra, $mafiyashapathpatra, $mukadma, $dhara, $thana, $janpad, $nyayalay, $sajavivaran, $sajaavadhi, $Ispanjikaranrashid, $panjikaranrashid, $photo, $signature, $name, $address, $date, $Status, $Reason, $RegistrationNo,$strdibarfirm,$strkalisuchifirm,$strarthdandfirm,$strfu_stoff,$strvikashkarya,$stranubhavpramarpatra,$strmafiyashapathpatra,$strpanjikaranrashid,$Isvikashkarya1,$Isanubhavpramarpatra1,$Ismafiyashapathpatra1,$Ispanjikaranrashid1;
   
   $query="select * from contractors where ContractorId='".$cid."'";
    
   $db->query($query);
   if($db->num_rows())
   {
		  	  $rows=$db->fetch_array();
              $ContractorName=$rows['ContractorName'];
              $Email=$rows['Email'];
              $Phone=$rows['Phone'];
              $password=$rows['password'];
              $rastriyata=$rows['rastriyata'];
              $jati=$rows['jati'];
              $dharm=$rows['dharm'];

              $Isnewcharitrapramanpatra=$rows['Isnewcharitrapramanpatra'];
              if($Isnewcharitrapramanpatra=='yes'){
			     $Isnewcharitrapramanpatra1='layXu gS';
				 $newcharitrapramanpatra=$rows['newcharitrapramanpatra'];
				 if($newcharitrapramanpatra!=''){
					 $howoldnewcharitrapramanpatra=$rows['howoldnewcharitrapramanpatra'];
					 $howoldnewcharitrapramanpatraLink="<a href='../newcharitrapramanpatra/$newcharitrapramanpatra' target='_new'>View</a>";
					 if($howoldnewcharitrapramanpatra==24){
					    $strhowoldnewcharitrapramanpatra='2 o’kZ iqjkuk';
					 }else if($howoldnewcharitrapramanpatra==24){
					    $strhowoldnewcharitrapramanpatra='1 o’kZ iqjkuk';
					 }else{
						 $strhowoldnewcharitrapramanpatra='6 ekg iqjkuk';
					 }
					 $strhowoldnewcharitrapramanpatra1="<tr>    
            <td width='30%'>&nbsp;</td>    
            <td align='left' >
                    <table cellspacing='0' cellpadding='0' border='0'>
					<tr><td width='40%'>A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>izksijkbVj dk uke</span>:</td><td><span style='font-family: kruti_dev_010regular;font-size:15px;'>&nbsp;$propwriter</span></td></tr>
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Isnewcharitrapramanpatra1='layXu ugha';
			  }

			  $Isshapathpatra=$rows['Isshapathpatra'];
              if($Isshapathpatra=='yes'){
			     $Isshapathpatra1='layXu gS';
				 $shapathpatra=$rows['shapathpatra'];
				 if($shapathpatra!=''){
					 $propwriter=$rows['propwriter'];
					 $propwritersthaipata=$rows['propwritersthaipata'];
					 $propwriterpatrapata=$rows['propwriterpatrapata'];
					 $propwriterpartner=$rows['propwriterpartner'];
				     $shapathpatraLink="<a href='../sapathpatra/$shapathpatra' target='_new'>View</a>";
					 $strsapthpatra="<tr>    
                
            <td align='left' colspan='2' >
                    <table cellspacing='0' cellpadding='0' width='100%' border='0'>
					<tr><td width='50%'>A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>izksijkbVj dk uke</span>:</td><td><span style='font-family: kruti_dev_010regular;font-size:15px;'>&nbsp;$propwriter</span></td></tr>

					<tr><td>B.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>izksijkbVj dk LFkk;h irk ¼Qksu ua0 lfgr½</span>:</td><td><span style='font-family: kruti_dev_010regular;font-size:15px;'>&nbsp;$propwritersthaipata</span></td></tr>

					<tr><td>C.&nbsp;<span style='font-family:kruti_dev_010regular;font-size:15px;'>izksijkbVj dk i= O;ogkj dk irk ¼Qksu ua0 lfgr½</span>:</td><td><span style='font-family: kruti_dev_010regular;font-size:15px;'>&nbsp;$propwriterpatrapata</span></td></tr>

					<tr><td>D.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>ikVZulZ ds uke o irs ¼Qksu ua0 lfgr½</span>:</td><td><span style='font-family: kruti_dev_010regular;font-size:15px;'>&nbsp;$propwriterpartner</span></td></tr>
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Isshapathpatra1='layXu ugha';
			  }

			  $Ishashiyatpramanpatra=$rows['Ishashiyatpramanpatra'];
              if($Ishashiyatpramanpatra=='yes'){
			     $Ishashiyatpramanpatra1='layXu gS';
				 $hashiyatpramanpatra=$rows['hashiyatpramanpatra'];
				 if($hashiyatpramanpatra!=''){
					 $hashiyatkirashi=$rows['hashiyatkirashi'];
					 $hashiyatkishreni=$rows['hashiyatkishreni'];
					 $hashiyatpramanpatraLink="<a href='../hashiyatpramanpatra/$hashiyatpramanpatra' target='_new'>View</a>";
					 $strhashiyatpramanpatra="<tr>    
            
            <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='40%'>A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>gSfl;r dh /kujkf”k ¼:i;k esa½</span>:</td><td>$hashiyatkirashi</td></tr>

					<tr><td>B.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>gSfl;r ds vuqlkj Js.kh</span>:</td><td>$hashiyatkishreni</td></tr>

					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Ishashiyatpramanpatra1='layXu ugha';
			  }


			  $Isitccirtificate=$rows['Isitccirtificate'];
              if($Isitccirtificate=='yes'){
			     $Isitccirtificate1='layXu gS';
				 $itccirtificate=$rows['itccirtificate'];
				 if($itccirtificate!=''){
					 $pancardno=$rows['pancardno'];
					 $aaykarreturnrashi=$rows['aaykarreturnrashi'];
					 $aaykarreturndate=$rows['aaykarreturndate'];
					 $itccirtificate="<a href='../itccirtificate/$itccirtificate' target='_new'>View</a>";
					 $stritccirtificate="<tr>    
            <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='40%'>A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>isu dkMZ ua0</span>:</td><td>$pancardno</td></tr>

					<tr><td>B.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>vk;dj fjVuZ dh /kujkf”k ¼:i;k esa½</span>:</td><td>$aaykarreturnrashi</td></tr>

					<tr><td>C.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>vk;dj fjVuZ dh izkfIr fnukad</span>:</td><td>$aaykarreturndate</td></tr>
					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Isitccirtificate1='layXu ugha';
			  }
 
              $Isfirmristedar=$rows['Isfirmristedar'];
              if($Isfirmristedar=='yes'){
			     $Isfirmristedar1='layXu gS';
				 $firmristedar=$rows['firmristedar'];
				 if($firmristedar!=''){
					 $ristedarname=$rows['ristedarname'];
					 $ristedarpadname=$rows['ristedarpadname'];
					 $ristedarpata=$rows['ristedarpata'];
					 $firmristedar="<a href='../firmristedar/$firmristedar' target='_new'>View</a>";
					 $strfirmristedar="<tr>    
            <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='40%'>A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zjr fj”rsnkj dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$ristedarname</td></tr>

					<tr><td>B.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zjr fj”rsnkj dk inuke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$ristedarpadname</td></tr>

					<tr><td>C.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zjr fj”rsnkj dk irk</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$ristedarpata</td></tr>
					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Isfirmristedar1='layXu ugha';
			  }

			  $Isfirmagainstkarywahi=$rows['Isfirmagainstkarywahi'];
              if($Isfirmagainstkarywahi=='yes'){
			     $Isfirmagainstkarywahi1='layXu gS';
				 $firmagainstkarywahi=$rows['firmagainstkarywahi'];
				 if($firmagainstkarywahi!=''){
					 $firmagainstkarywahi="<a href='../firmagainstkarywahi/$firmagainstkarywahi' target='_new'>View</a>";
					 $strkalisuchifirm="<tr>    
            <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='40%'>A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dkyh lwph ds lEcU/k esa QeZ@ikVZuj dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$kalisuchifirmname</td></tr>

					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>foHkkx dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$kalisuchifirmvibhag</td></tr>

					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zokgh dk dkj.k</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$kalisuchikaran</td></tr>
					
					</table>
			  </td>   
            </td>   
          </tr>";
		  $strdibarfirm="<tr>    
            <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='40%'>B.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>fMckj fd;s tkus ds lEcU/k esa QeZ@ikVZuj dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$dibarfirmname</td></tr>

					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>foHkkx dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$dibarfirmvibhag</td></tr>

					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zokgh dk dkj.k</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$dibarkaran</td></tr>
					
					</table>
			  </td>   
            </td>   
          </tr>";

		  $strarthdandfirm="<tr>    
             <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='40%'>C.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>;fn vFkZn.M vfHk;ksftr gqvk gS rks QeZ@ikVZuj dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$arthdandfirmname</td></tr>

					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>foHkkx dk uke</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$arthdandfirmvibhag</td></tr>

					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zokgh dk dkj.k</span>:</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>$arthdandkaran</td></tr>
					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Isfirmagainstkarywahi1='layXu ugha';
			  }

              $Isfu_stoff=$rows['Isfu_stoff'];
              if($Isfu_stoff=='yes'){
			     $fu_stoff1='layXu gS';
				 $fu_stoff=$rows['fu_stoff'];
				 if(!$fu_stoff!=''){
					 $sahayakno=$rows['sahayakno'];
					 $ourno=$rows['ourno'];
					 $supervisorno=$rows['supervisorno'];
					 $metno=$rows['metno'];
					 $anyano=$rows['anyano'];
					 $fu_stoff="<a href='../fu_stoff/$fu_stoff' target='_new'>View</a>";
					 $strfu_stoff="<tr>    
             <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td width='30%'><span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zjr lgk;d vfHk;Urk dh la[;k</span>:</td>
					<td>$sahayakno</td><td width='10%'>&nbsp;&nbsp;</td><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>voj vfHk;Urk dh la[;k</span>:</td><td>$ourno</td></tr>
					<tr><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>dk;Zjr lqijokbtj dh la[;k</span>:</td>
					<td>$supervisorno</td><td>&nbsp;&nbsp;</td><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>esV dh la[;k</span>:</td>
					<td>$metno</td></tr>
					<tr><td width='40%'><span style='font-family: kruti_dev_010regular;font-size:15px;'>vU; rduhdh LVkQ</span>:</td>
					<td>$anyano</td><td width='10%'>&nbsp;&nbsp;</td><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>csynkjksa dh la[;k</span>:</td>
					<td>$ourno</td></tr>

					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $fu_stoff1='layXu ugha';
			  }

			  $Isvikashkarya=$rows['Isvikashkarya'];
              if($Isvikashkarya=='yes'){
			     $Isvikashkarya1='layXu gS';
				 $vikashkarya=$rows['vikashkarya'];
				 if($vikashkarya!=''){
					 $sarkarivibhagvivaran=$rows['sarkarivibhagvivaran'];
					 $sarkarivibhagrashi=$rows['sarkarivibhagrashi'];
					 $manyatasansthavivaran=$rows['manyatasansthavivaran'];
					 $manyatasanstharashi=$rows['manyatasanstharashi'];
					 
					 $vikashkarya="<a href='../vikashkarya/$vikashkarya' target='_new'>View</a>";
					 $strvikashkarya="<tr>    
             <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td >A.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>ljdkjh foHkkx dk uke fMohtu dk fooj.k</span>:</td>
					<td>$sarkarivibhagvivaran</td><td width='10%'>&nbsp;&nbsp;</td><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>rFkk /kujkf”k ¼:i;k esa½</span>:</td><td>$sarkarivibhagrashi</td></tr>
					<tr><td >B.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>ekU;rk izkIr laLFkk dk uke fMohtu dk fooj.k</span>:</td>
					<td>$manyatasansthavivaran</td><td width='10%'>&nbsp;&nbsp;</td><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>rFkk /kujkf”k ¼:i;k esa½</span>:</td>
					<td>$manyatasanstharashi</td></tr>
					
					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Isvikashkarya1='layXu ugha';
			  }

			  $Isanubhavpramarpatra=$rows['Isanubhavpramarpatra'];
              if($Isanubhavpramarpatra=='yes'){
			     $Isanubhavpramarpatra1='layXu gS';
				 $anubhavpramarpatra=$rows['anubhavpramarpatra'];
				 if($anubhavpramarpatra!=''){
					 $anubhavpramarpatra="<a href='../anubhavpramarpatra/$anubhavpramarpatra' target='_new'>View</a>";
					
				 }
			  }else{
			     $Isanubhavpramarpatra1='layXu ugha';
			  }
			  
              
			  $Ismafiyashapathpatra=$rows['Ismafiyashapathpatra'];
              if($Ismafiyashapathpatra=='yes'){
			     $Ismafiyashapathpatra1='layXu gS';
				 $mafiyashapathpatra=$rows['mafiyashapathpatra'];
				 if($mafiyashapathpatra!=''){
					 $mukadma=$rows['mukadma'];
					 $dhara=$rows['dhara'];
					 $thana=$rows['thana'];
					 $janpad=$rows['janpad'];
					 $nyayalay=$rows['nyayalay'];
					 $sajavivaran=$rows['sajavivaran'];
					 $sajaavadhi=$rows['sajaavadhi'];
					 
					 $mafiyashapathpatra="<a href='../mafiyashapathpatra/$mafiyashapathpatra' target='_new'>View</a>";
					 $strmafiyashapathpatra="<tr>    
             <td align='left' colspan='2'>
                    <table cellspacing='0' cellpadding='0' border='0' width='100%'>
					<tr><td >1.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>eqdnek ua0</span>:</td>
					<td>$mukadma</td><td width='10%'>&nbsp;&nbsp;</td><td >2.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>/kkjk;s</span>:</td><td>$dhara</td></tr>
					<tr><td >3.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>Fkkuk</span>:</td>
					<td>$thana</td><td width='10%'>&nbsp;&nbsp;</td><td >4.<span style='font-family: kruti_dev_010regular;font-size:15px;'>tuin</span>:</td>
					<td>$janpad</td></tr>
					<tr><td >5.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>U;k;y; tgka eqdnek py jgk gS</span>:</td>
					<td>$nyayalay</td><td width='10%'>&nbsp;&nbsp;</td><td >6.<span style='font-family: kruti_dev_010regular;font-size:15px;'>tsy esa fu:) jgus dh vof/k</span>:</td>
					<td>$sajaavadhi</td></tr>
					<tr><td >7.&nbsp;<span style='font-family: kruti_dev_010regular;font-size:15px;'>izkIr ltk dk fooj.k</span>:</td>
					<td colspan='2'><span style='font-family: kruti_dev_010regular;font-size:15px;'>$sajavivaran</span></td></tr>
					
					
					</table>
			  </td>   
            </td>   
          </tr>";
				 }
			  }else{
			     $Ismafiyashapathpatra1='layXu ugha';
			  }

			  $Ispanjikaranrashid=$rows['Ispanjikaranrashid'];
              if($Ispanjikaranrashid=='yes'){
			     $Ispanjikaranrashid1='layXu gS';
				 $panjikaranrashid=$rows['panjikaranrashid'];
				 if($panjikaranrashid!=''){
					 $panjikaranrashid="<a href='../panjikaranrashid/$panjikaranrashid' target='_new'>View</a>";
					
				 }
			  }else{
			     $Ispanjikaranrashid1='layXu ugha';
			  }
			  
			  $firmEngName1=$rows['firmEngName1'];
			  $firmEngPata1=$rows['firmEngPata1'];
			  $firmEngPhone1=$rows['firmEngPhone1'];
			  $firmEngEmail1=$rows['firmEngEmail1'];
			  $firmEngName2=$rows['firmEngName2'];
			  $firmEngPata2=$rows['firmEngPata2'];
			  $firmEngPhone2=$rows['firmEngPhone2'];
			  $firmEngEmail2=$rows['firmEngEmail2'];
			  
			  $password=$rows['password'];
			  $photo=$rows['photo'];
			  $signature=$rows['signature'];
			  $name=$rows['name'];
			  $address=$rows['address'];
			  $date=$rows['date'];
			  $password=$rows['password'];

			  if($photo!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$photo="<img src='/jnncivil/photo/thumbs/".$photo."' >";
		}
		else
		{
			$photo="<img src='/jnncivil1/photo/thumbs/".$photo."' >";
		}

		if($signature!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$signature="<img src='/jnncivil/signature/thumbs/".$signature."' >";
		}
		else
		{
			$signature="<img src='/jnncivil1/signature/thumbs/".$signature."' >";
		}
		}
   }
              
			  $cid=$cid;
              $Status=$rows['Status'];
              if($Status=='Approved'){
			     $Approved='selected';
			  }elseif($Status=='Cancelled'){
				 $Cancelled='selected';
			  }else{
				  $Pending='selected';
			  }
			  
			  
   }
 }

 

function ChangeStatus( $db,$cid)
{
  
   global $PROMPT;

	$id =  $cid;
    $status=$_POST['Status'];
    $Reason=$_POST['Reason'];
 	$change = "update contractors set Status='$status',Reason='$Reason',RegistrationNo='$RegistrationNo' where ContractorId in ($id)";
	$db->query($change);

	$total = $db->affected_rows();
	if($status!='Pending'){
	  $PROMPT = "Contractor has been ".$status.".";
	}else{
	  $PROMPT = "Contractor is Pending.";
	}
}
?>

