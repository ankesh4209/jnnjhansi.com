<?php error_reporting(E_ALL);?>
<?php
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to Jhansi Nagar Nigam";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

if($_GET['Dept']=='Single'){
	$radioDept=$_GET['Dept'];
	$radioSingle='checked';
	$deptid=$_GET['deptName'];
    GetDepartment($db,$deptid);
}else{
	$radioDept=$_GET['Dept'];
	$radioAll='checked';
}

if(ISSET($_GET['showreport'])){

viewreports($db,$db1,$db2,$deptid,$radioDept,$radioSingle,$radioAll);
}
 
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/emp_postwise.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewreports($db,$db1,$db2,$dept_id='',$radioDept,$radioSingle,$radioAll)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$db1,$db2,$db3,$db4,$db5;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$department,$strdept,$emp_name,$emp_code,$sno,$EmployeePhoto,$ResidentialAdd;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/empinfoDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=1;
	 $lastrow=$MAX+$page;
	 //$deptid,$radioDept,$radioSingle,$radioAll
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
	          $department=$rows['DeptName'];
			  $strdept="<tr ><td  colspan='3' align='center'><b><span style='font-family: kruti_dev_010regular;font-size:20px;'>foHkkx</span> :<span style='font-family: kruti_dev_010regular;font-size:20px;'>".$department."</span></b></td><tr>";
			  
			  $query1="select * from employeeinfo where Department='".$rows['DeptId']."' order by EmployeeId ASC";
			  
				  $res2=$db1->query($query1);
				  $num=$db1->num_rows($res);			 	
				  if($num>0){
					  $sno=1;
					  
					  while($rows1 = $db1->fetch_array())
					  {
							$emp_name=stripslashes($rows1['EmployeeName']);
							$emp_code=stripslashes($rows1['EmployeeCode']);
							$EmployeePhoto=stripslashes($rows1['EmployeePhoto']);
							$ResidentialAdd=stripslashes(nl2br($rows1['ResidentialAdd']));
							GetPosts($db2,$rows1['JoinningPost']);

							if($EmployeePhoto!=''){
								if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
								{
									$EmployeePhoto="<img src='/pis/emp_pics/thumbs/".$EmployeePhoto."' >";
								}
								else
								{
									$EmployeePhoto="<img src='/pis/emp_pics/thumbs/".$EmployeePhoto."' >";
								}
							   
						   }else{
								$EmployeePhoto='';
						   }
					  
							ReplaceContent(Array("S1"));
							$PRODUCT_LIST.=$S1;
							$S1 = $S2;

						$sno++;
					
					}
				  
					  



						$slno++;	  
				  }else{
						$PRODUCT_LIST="<tr><td colspan='4' align='center'>No Employee Found.</td></tr>";
				  }
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<a href='emp_info.php?showreport=".$_GET['showreport']."&Dept=".$_GET['Dept']."&deptName=".$_GET['deptName']."&page=$prevpage&max=$MAX' ><< Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='emp_info.php?showreport=".$_GET['showreport']."&Dept=".$_GET['Dept']."&deptName=".$_GET['deptName']."&page=$lastrow&max=$MAX' >Next>></a>";
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
					$PAGE_NAVS.=" <a href='emp_info.php?showreport=".$_GET['showreport']."&Dept=".$_GET['Dept']."&deptName=".$_GET['deptName']."&page=$i&max=$MAX' >$toPrint</a>";
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
      $list_dept.="<select name='deptName' style='font-family: kruti_dev_010regular;font-size:20px;'>";
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
	   $list_dept.="</select>";
    }
 }

 function GetPosts($db2,$PostId){
	global $empPost;
	$sql="select Post from posts where PostId='$PostId'";
    $row=$db2->query($sql);
    $res=$db2->fetch_array($row);
    $empPost = $res['Post'];
      
}

?>