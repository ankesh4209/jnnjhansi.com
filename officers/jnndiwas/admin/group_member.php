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

if($_REQUEST["SEARCH_GROUP"]!="" or $_REQUEST['group']!="")
 {
   $search_text1=$_REQUEST['group'];
 }
else
 {
   $search_text1="";
 }
 
if($_POST["submit"]!="")
 {
   $off_id1=explode("-",$_POST['officer']);
   $off_id=$off_id1[0];
   $type=$off_id1[1];
   $grp_id=$_POST['group_add'];
   
    $insert="insert into group_member (group_id,member_id,type_id) values('$grp_id','$off_id','$type')";
    $db->query($insert);
    
    $PROMPT="Officer Added in Group";
 }

GetGroup($db);
selectofficer($db);
selectConofficer($db);
viewGroupMember($db,$db1,$search_text1);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/group_member.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function viewGroupMember($db,$db1,$search_text1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$name,$contact_no,$group,$row_color;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/groupmemberGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=50;
	  $lastrow=$MAX+$page;
	  $color1="#6495ED";
	  $color2="#F0E68C";
	 
	 if($search_text1!="")
	  {
      $search_text=" and group_id='$search_text1'";
    }
   else
    {
      $search_text="";
    }
	 
	 $count="select count(g_id) as total from group_member where 1=1 $search_text";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from group_member where 1=1 $search_text  limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $row_color = ($slno % 2) ? $color1 : $color2;
			  $groupid=$rows['group_id'];
			  $fid=$rows['g_id'];
			  
			  $sql1="select * from group_name where group_id='$groupid'";
        $row1=$db1->query($sql1);
        $res1=$db1->fetch_array($row1);
        $group = $res1['group_name'];
    
         
			  $off_id=$rows['member_id'];
			  $type=$rows['type_id'];
        if($type==1)
        {
         $sql1="select off_desi,officer_contno from officer where o_id='$off_id'";
	       $db1->query($sql1);
	       $rows1=$db1->fetch_array();
	       $name=$rows1['off_desi'];
	       $contact_no=$rows1['officer_contno'];
	      }
	      else
	      {
         $sql1="select off_desi,officer_contno from con_officer where c_id='$off_id'";
	       $db1->query($sql1);
	       $rows1=$db1->fetch_array();
	       $name=$rows1['off_desi'];
	       $contact_no=$rows1['officer_contno'];
        }
			  
			  ReplaceContent(Array("S1"));
	   	  $NEWS_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='group_member.php?page=$prevpage&max=$MAX&$next_links&group=$search_text1' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='group_member.php?page=$lastrow&max=$MAX&$next_links&group=$search_text1' >Next></a>";
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
          $PAGE_NAVS.=" <a href='group_member.php?page=$i&max=$MAX&left_id=1&$next_links&group=$search_text1' >$toPrint</a> |";
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
 	$delete = "delete from group_member where g_id in ($officer)";
	$db->query($delete);
	$total = $db->affected_rows();
	$PROMPT = "Total $total Group Members records have been deleted.";
 }

function selectofficer($db)
 {	
	global $s_officer;
  
    $sql="select * from officer";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $oid = $res['o_id'];
         $officer = $res['off_desi'];
         $s_officer.="<option value='$oid-1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$officer</option>";
       }
    }
 }

function selectConofficer($db)
 {	
	global $con_officer;
  
    $sql="select * from con_officer";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $oid = $res['c_id'];
         $officer = $res['officer_name'];
         $con_officer.="<option value='$oid-2'>$officer</option>";
       }
    }
 }

function GetGroup($db)
 {
  global $groupname;
  
    $sql="select * from group_name";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $gid = $res['group_id'];
         $gname = $res['group_name'];
         $groupname.="<option value='$gid'>$gname</option>";
       }
    }
 
 }
?>
