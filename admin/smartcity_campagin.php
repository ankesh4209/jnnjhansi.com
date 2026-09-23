<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
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


$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());



$class11="leftab_on";
$class12="leftab_off";


viewSmartCityCampagin($db);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_campagin.html");
if($_SESSION['type']==1) {
  $TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
} else {
 $TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home1.html");
}

$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewSmartCityCampagin($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$id,$name,$address,$mobile,$pages,$gender,$age,$ward_no;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_campaginGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=20;
	 $lastrow=$MAX+$page;
	 
	 
	 $count="select count(id) as total from smartcity_campagin";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from smartcity_campagin order by id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $id=$rows['id'];
			 
			  $name=$rows['name'];
			  $address=$rows['address'];
			  $mobile=$rows['mobile'];
			  $gender=$rows['gender'];
			  $age=$rows['age'];
			  $ward_no=$rows['ward_no'];
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='smartcity_campagin.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='smartcity_campagin.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='smartcity_campagin.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		$pages="Pages:";
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='8'>No Notice Found</td></tr>";
    }
   
  return 1;
 }
 


?>
