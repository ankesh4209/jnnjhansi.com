<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!="")
{
 $CorporatorType=$_POST['Corporator'];
 
 SearchCorporator($db,$CorporatorType);

 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/search.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/search.html");
 SearchCorporator($db,$CorporatorType='');
}

ViewCorpratorType($db,$CorporatorType);

$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

 function ViewCorpratorType($db,$CorporatorTypeId)
 {
	 global $corporator;
    $sql="select * from corporatortype";
    $result=$db->query($sql);
    while($row = $db->fetch_array($result))
	  {
		  $c_id=$row['CorporatorTypeId'];
		  $CorporatorType=$row['CorporatorType'];
		  if($CorporatorTypeId==$c_id){
			$corporator.="<option value='$c_id' selected>$CorporatorType</option>";
		  }else{
			$corporator.="<option value='$c_id'>$CorporatorType</option>";
		  }
	  }
 }

function SearchCorporator($db,$CorporatorType)
 {
   global $parsad_list;
   
   if($CorporatorType=="3" )
        $q_compl=" and Sex='efgyk'";
   elseif($CorporatorType=="1")
	   $q_compl=" and Sex='iq#’k'";
   elseif($CorporatorType!="")
     $q_compl=" and CorporatorType='$CorporatorType'";
   else
     $q_compl="";
     
    
   $sql="select * from corporators where 1=1 $q_compl order by CorporatorType DESC,WardNo ASC ";
   $res=$db->query($sql);
   if($db->num_rows())
		{
		  $slno=1;
			while($rows = $db->fetch_array($res))
			{
			  
              $CorporatorName=stripslashes($rows['CorporatorName']);
              $RelationTag=stripslashes($rows['RelationTag']);
              $FatherName=stripslashes($rows['FatherName']); 
              $OfficeNo=stripslashes($rows['OfficeNo']);
              $MobileNo=stripslashes($rows['MobileNo']);
              $WardNo=stripslashes($rows['WardNo']);
              $WardName=stripslashes($rows['WardName']);
              $Designation=stripslashes($rows['Designation']);
              $Sex=stripslashes($rows['Sex']);
              $Mohalla=stripslashes($rows['Mohalla']);
               $CorporatorId=stripslashes($rows['CorporatorId']);
			  $cid=$CorporatorId;
			  
				
//$print_status="<input type='button' name='view' value='View' onclick=\"window.open('corporator_details.php?cid=$cid','windowname1','width=650, height=550,scrollbars=yes'); return false;\"><a href=\"javascript: void(0)\" onclick=\"window.open('admin/print_complete.php?aid=$aid','windowname1','width=650, height=550,scrollbars=yes'); return false;\">Print</a>";
        
      
     $parsad_list.="<tr>
                        <td><input type='button' name='view' value='View' onclick=\"window.location.href='corporator_details.php?cid=$cid'\"></td>
						<td>$slno</td>
                        <td align='center'><div>$WardNo</div></td>
                       <td><div style='font-family: kruti_dev_010regular;font-size:20px;'>$WardName</div></td>
                        <td><div style='font-family: kruti_dev_010regular;font-size:20px;'>$CorporatorName</div></td>
						<td><div>$RelationTag&nbsp;&nbsp;<span style='font-family: kruti_dev_010regular;font-size:20px;'>$FatherName</span></div></td>
                        <td><div><span style='font-family: kruti_dev_010regular;font-size:20px;'>dk;kZy;</span>: $OfficeNo<div><div><span style='font-family: kruti_dev_010regular;font-size:20px;
						'>eksckby</span>: $MobileNo<div></td>
                        
                       </tr>";
        
				$slno++;
			
			}
			
		 
		
		}
   else
   {
     $parsad_list="<tr>
                        <td colspan='6' align='center'>No Record Found</td>
                       </tr>";
   }
 }

?>
