<?php @session_start(); ?>

<?php 
 if ($_SESSION['username']=='')
  {	
    header ("Location: index.php"); 				
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

 $fid = $_REQUEST['fid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($fid))
	{
		deleteofficer($fid, $db);
	}
}

if($_POST["submit"]!="")
 {
   $off_id=$_POST['officer'];
   $grp_id=$_POST['group'];
   
    $insert="insert into group_member (group_id,member_id,type_id) values('$grp_id','$off_id','2')";
    $db->query($insert);
    
    $PROMPT="Concern Officer Add in Group";
 }

selectConofficer($db);
viewGroupMember($db,$db1);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/congroup_member.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function viewGroupMember($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$name,$contact_no,$group,$row_color;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/congroupmemberGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=10;
	 $lastrow=$MAX+$page;
	  $color1="#6495ED";
	  $color2="#F0E68C";
	 
	 
	 $count="select count(g_id) as total from group_member where type_id='2'";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from group_member where type_id='2' limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $row_color = ($slno % 2) ? $color1 : $color2;
			  $group=$rows['group_id'];
			  $off_id=$rows['member_id'];
        
        $sql1="select officer_name,officer_contno from con_officer where c_id='$off_id'";
	      $db1->query($sql1);
	      $rows1=$db1->fetch_array();
	      $name=$rows1['officer_name'];
	      $contact_no=$rows1['officer_contno'];
			  
			  ReplaceContent(Array("S1"));
	   	  $NEWS_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='group_member.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='group_member.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
			}
							
			$PAGE_NAVS="";
			for($i=0,$toPrint=1;$i<	$TOTAL_RECORDSET;$i+=$MAX,$toPrint++)
			{	
       if ($lastrow-$i==$MAX)
				{	
                   $PAGE_NAVS.=" <B>".$toPrint."</b> | ";
					$CURRENT_PAGE_NO = $toPrint;
				}
				else
				{	
          $PAGE_NAVS.=" <a href='group_member.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $NEWS_LIST="<tr><td colspan='5' height='25' valign='middle' align='center'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteofficer($fid, $db)
 {	
	global $PROMPT;
 	$officer = implode("," ,$fid);
 	$delete = "delete from con_officer where c_id in ($officer)";
	$db->query($delete);
	$total = $db->affected_rows();
	$PROMPT = "Total $total Officer records have been deleted.";
 }

function selectConofficer($db)
 {	
	global $s_officer;
  
    $sql="select * from con_officer";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $oid = $res['c_id'];
         $officer = $res['officer_name'];
         $s_officer.="<option value='$oid'>$officer</option>";
       }
    }
 }




?>
