<?php error_reporting(E_ALL);?>
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
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());

$db4=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db4->open() or die($db4->error());

$db5=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db5->open() or die($db5->error());

$db6=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db6->open() or die($db6->error());

$pid = $_REQUEST['pid'];

$class1="";
$class2="";
$class3="current";

$deptid=$_GET['Department'];


GetDepartment($db,$deptid);

viewreports($db,$db1,$db2,$db3,$db4,$db5,$db6,$deptid);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/emp_summary.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR","BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewreports($db,$db1,$db2,$db3,$db4,$db5,$db6,$dept_id='')
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$db1,$db2,$db3,$db4,$db5;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$department,$total_emp,$tot_gen1,$tot_gen2,$tot_gen3,$tot_gen4,$tot_gen5,$total_handi1,$total_handi2,$total_handi3,$total_handi4,$total_handi5,$total_obc1,$total_obc2,$total_obc3,$total_obc4,$total_obc5,$total_sc1,$total_sc2,$total_sc3,$total_sc4,$total_sc5,$total_st1,$total_st2,$total_st3,$total_st4,$total_st5,$Total1,$Total2,$Total3,$Total4,$Total5;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/repDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=2;
	 $lastrow=$MAX+$page;
	 
	 $sql='';  
	if($dept_id!=''){
		$sql.=" and DeptId = '$dept_id'";
		
	}
	
	 $count="select count(DeptId) as total from department where 1".$sql;
     $db->query($count);
	 $row = $db->fetch_assoc();
     $TOTAL_RECORDSET = $row['total'];
  
     $query="select * from department where 1".$sql." order by DeptName ASC limit  $page, $MAX ";
    
     $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			  
			while($rows = $db->fetch_array())
			{
	          $sql="select count(*) as Total from employeeinfo where Department='".$rows['DeptId']."'";
			  $db6->query($sql);
			  $emp = $db6->fetch_array();	
              $total_emp=$emp['Total'];
				
			  $department=$rows['DeptName'];
			  
				  $sql_gen="select count(*) as General from employeeinfo where Category='General' and JobType='Central Seva' and Department='".$rows['DeptId']."'";
				  $db1->query($sql_gen);
				  $row_gen = $db1->fetch_array();
				  $tot_gen1=$row_gen['General'];

				  $sql_gen="select count(*) as General from employeeinfo where Category='General' and JobType='Non Central Seva' and Department='".$rows['DeptId']."'";
				  $db1->query($sql_gen);
				  $row_gen = $db1->fetch_array();
				  $tot_gen2=$row_gen['General'];

				  $sql_gen="select count(*) as General from employeeinfo where Category='General' and JobType='Mandal Up Seva' and Department='".$rows['DeptId']."'";
				  $db1->query($sql_gen);
				  $row_gen = $db1->fetch_array();
				  $tot_gen3=$row_gen['General'];

				  $sql_gen="select count(*) as General from employeeinfo where Category='General' and JobType='Nideshalaya Karmchari' and Department='".$rows['DeptId']."'";
				  $db1->query($sql_gen);
				  $row_gen = $db1->fetch_array();
				  $tot_gen4=$row_gen['General'];




				  $sql_handi="select count(*) as Handicap from employeeinfo where ReservedCategory='Handicapped' and JobType='Central Seva' and Department='".$rows['DeptId']."'";
				  $db2->query($sql_handi);
				  $row_handi = $db2->fetch_array();	
				  $total_handi1=$row_handi['Handicap'];
				  
				  $sql_handi="select count(*) as Handicap from employeeinfo where ReservedCategory='Handicapped' and JobType='Non Central Seva' and Department='".$rows['DeptId']."'";
				  $db2->query($sql_handi);
				  $row_handi = $db2->fetch_array();	
				  $total_handi2=$row_handi['Handicap'];

				  $sql_handi="select count(*) as Handicap from employeeinfo where ReservedCategory='Handicapped' and JobType='Mandal Up Seva' and Department='".$rows['DeptId']."'";
				  $db2->query($sql_handi);
				  $row_handi = $db2->fetch_array();	
				  $total_handi3=$row_handi['Handicap'];

				  $sql_handi="select count(*) as Handicap from employeeinfo where ReservedCategory='Handicapped' and JobType='Nideshalaya Karmchari' and Department='".$rows['DeptId']."'";
				  $db2->query($sql_handi);
				  $row_handi = $db2->fetch_array();	
				  $total_handi4=$row_handi['Handicap'];





				  $sql_obc="select count(*) as obc from employeeinfo where Category='OBC' and JobType='Central Seva' and Department='".$rows['DeptId']."'";
				  $db3->query($sql_obc);
				  $row_obc = $db3->fetch_array();	
				  $total_obc1=$row_obc['obc'];

				  $sql_obc="select count(*) as obc from employeeinfo where Category='OBC' and JobType='Non Central Seva' and Department='".$rows['DeptId']."'";
				  $db3->query($sql_obc);
				  $row_obc = $db3->fetch_array();	
				  $total_obc2=$row_obc['obc'];

				  $sql_obc="select count(*) as obc from employeeinfo where Category='OBC' and JobType='Mandal Up Seva' and Department='".$rows['DeptId']."'";
				  $db3->query($sql_obc);
				  $row_obc = $db3->fetch_array();	
				  $total_obc3=$row_obc['obc'];

				  $sql_obc="select count(*) as obc from employeeinfo where Category='OBC' and JobType='Nideshalaya Karmchari' and Department='".$rows['DeptId']."'";
				  $db3->query($sql_obc);
				  $row_obc = $db3->fetch_array();	
				  $total_obc4=$row_obc['obc'];



				  $sql_sc="select count(*) as sc from employeeinfo where Category='SC' and JobType='Central Seva' and Department='".$rows['DeptId']."'";
				  $db4->query($sql_sc);
				  $row_sc = $db4->fetch_array();	
				  $total_sc1=$row_sc['sc'];

				  $sql_sc="select count(*) as sc from employeeinfo where Category='SC' and JobType='Non Central Seva' and Department='".$rows['DeptId']."'";
				  $db4->query($sql_sc);
				  $row_sc = $db4->fetch_array();	
				  $total_sc2=$row_sc['sc'];

				  $sql_sc="select count(*) as sc from employeeinfo where Category='SC' and JobType='Mandal Up Seva' and Department='".$rows['DeptId']."'";
				  $db4->query($sql_sc);
				  $row_sc = $db4->fetch_array();	
				  $total_sc3=$row_sc['sc'];

				  $sql_sc="select count(*) as sc from employeeinfo where Category='SC' and JobType='Nideshalaya Karmchari' and Department='".$rows['DeptId']."'";
				  $db4->query($sql_sc);
				  $row_sc = $db4->fetch_array();	
				  $total_sc4=$row_sc['sc'];




				  $sql_st="select count(*) as st from employeeinfo where Category='ST' and JobType='Central Seva' and Department='".$rows['DeptId']."'";
				  $db5->query($sql_st);
				  $row_st = $db5->fetch_array();	
				  $total_st1=$row_st['st'];

				  $sql_st="select count(*) as st from employeeinfo where Category='ST' and JobType='Non Central Seva' and Department='".$rows['DeptId']."'";
				  $db5->query($sql_st);
				  $row_st = $db5->fetch_array();	
				  $total_st2=$row_st['st'];

				  $sql_st="select count(*) as st from employeeinfo where Category='ST' and JobType='Mandal Up Seva' and Department='".$rows['DeptId']."'";
				  $db5->query($sql_st);
				  $row_st = $db5->fetch_array();	
				  $total_st3=$row_st['st'];

				  $sql_st="select count(*) as st from employeeinfo where Category='ST' and JobType='Nideshalaya Karmchari' and Department='".$rows['DeptId']."'";
				  $db5->query($sql_st);
				  $row_st = $db5->fetch_array();	
				  $total_st4=$row_st['st'];

				  $Total1=$tot_gen1+$total_handi1+$total_obc1+$total_sc1+$total_st1;
				  $Total2=$tot_gen2+$total_handi2+$total_obc2+$total_sc2+$total_st2;
				  $Total3=$tot_gen3+$total_handi3+$total_obc3+$total_sc3+$total_st3;
				  $Total4=$tot_gen4+$total_handi4+$total_obc4+$total_sc4+$total_st4;
				 
			    ReplaceContent(Array("S1"));
			  
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<a href='emp_summary.php?page=$prevpage&max=$MAX' ><< Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='emp_summary.php?page=$lastrow&max=$MAX' >Next>></a>";
			}
							
			$PAGE_NAVS="";
			for($i=0,$toPrint=1;$i<	$TOTAL_RECORDSET;$i+=$MAX,$toPrint++)
			{	
				if ($lastrow-$i==$MAX)
				{	
					$PAGE_NAVS.="<span class='current'>".$toPrint."</span>";
					$CURRENT_PAGE_NO = $toPrint;
				}
				else
				{	
					$PAGE_NAVS.=" <a href='emp_summary.php?page=$i&max=$MAX' >$toPrint</a>";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
			$PRODUCT_LIST="<tr><td colspan='7'>No Employee Found.</td></tr>";
        }
   
  return 1;
 }
 
function GetDepartment($db,$deptid)
 {	
	global $list_dept;
  
    $sql="select * from department order by DeptName asc";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $did= $res['DeptId'];
         $d_name = $res['DeptName'];
		 if($deptid==$did){
		    $list_dept.="<option value='$did' selected>$d_name</option>";
		 }else{
			$list_dept.="<option value='$did'>$d_name</option>";
		 }
       }
    }
 }
?>