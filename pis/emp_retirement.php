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
//		print_r($_GET);
if($_GET['Dept']=='Single'){
	$radioDept=$_GET['Dept'];
	$radioSingle='checked';
	$deptid=$_GET['deptName'];
    GetDepartment($db,$deptid);
}else{
	$radioDept=$_GET['Dept'];
	$radioAll='checked';
}

if($_GET['Retirement']=='Y'){
	$radioRetirement=$_GET['Retirement'];
	$RetireIn='checked';
	$RetiringMonth=$_GET['RetiringMonth'];
	$RetiringYear=$_GET['RetiringYear'];
	if($RetiringMonth=='1'){
		$Month1='selected';
	}elseif($RetiringMonth=='2'){
		$Month2='selected';
	}elseif($RetiringMonth==3){
		$Month3='selected';
	}elseif($RetiringMonth=='4'){
		$Month4='selected';
	}elseif($RetiringMonth=='5'){
		$Month5='selected';
	}elseif($RetiringMonth=='6'){
		$Month6='selected';
	}elseif($RetiringMonth=='7'){
		$Month7='selected';
	}elseif($RetiringMonth=='8'){
		$Month8='selected';
	}elseif($RetiringMonth=='9'){
		$Month9='selected';
	}elseif($RetiringMonth=='10'){
		$Month10='selected';
	}elseif($RetiringMonth=='11'){
		$Month11='selected';
	}elseif($RetiringMonth=='12'){
		$Month12='selected';
	}
	$MonthList='<select name="RetiringMonth" style="width:100px;">
				<option value="">&lt;--Month--&gt;</option>
				<option value="1" '.$Month1.'>January</option>
				<option value="2" '.$Month2.'>February</option>
				<option value="3" '.$Month3.'>March</option>
				<option value="4" '.$Month4.'>April</option>
				<option value="5" '.$Month5.'>May</option>
				<option value="6" '.$Month6.'>June</option>
				<option value="7" '.$Month7.'>July</option>
				<option value="8" '.$Month8.'>August</option>
				<option value="9" '.$Month9.'>September</option>
				<option value="10" '.$Month10.'>October</option>
				<option value="11" '.$Month11.'>November</option>
				<option value="12" '.$Month12.'>December</option></select>';

	$year_list='<select name="RetiringYear"><option value=""><--Year--></option>';
	for($j=2013;$j<=2080;$j++){
		if($RetiringYear==$j){
			$year_list.="<option value=".$j." selected>".$j."</option>";
		}else{
			$year_list.="<option value=".$j.">".$j."</option>";
		}
	}
	
}else{
	$radioRetirement=$_GET['Retirement'];
	$Retire='checked';
}



if(ISSET($_GET['showreport'])){
viewreports($db,$db1,$db2,$deptid,$radioDept,$radioSingle,$radioAll,$radioRetirement,$Retire,$RetiringYear,$RetiringMonth);
}
 
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/emp_retirement.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewreports($db,$db1,$db2,$deptid,$radioDept,$radioSingle,$radioAll,$radioRetirement,$Retire,$RetiringYear,$RetiringMonth)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$db1,$db2,$db3,$db4,$db5;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$department,$strdept,$emp_name,$emp_code,$sno,$EmployeePhoto,$ResidentialAdd,$DateOfRetirement,$MobileNo;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/retirementDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=1;
	 $lastrow=$MAX+$page;
	 $sql='';  
	if($deptid!=''){
		$sql.=" and DeptId = '$deptid'";
	}
    if($radioRetirement=='N')
			$sql1.=" and DateOfRetirement < CURDATE()";
	else{  
		if($RetiringYear!='' && $RetiringMonth!=''){
			$sql1.=" and month(DateOfRetirement)='".$RetiringMonth."' and year(DateOfRetirement)='".$RetiringYear."'";
		}
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
			  $strdept="<tr><td  colspan='3' align='center'><b><span style='font-family: kruti_dev_010regular;font-size:20px;'>foHkkx</span> :<span style='font-family: kruti_dev_010regular;font-size:20px;'>".$department."</span></b></td><tr>";
			  
			  $query1="select * from employeeinfo where 1 ".$sql1." and Department='".$rows['DeptId']."' order by EmployeeId ASC";
			 //echo "<br>".$query1;
				  $res2=$db1->query($query1);
				  $num=$db1->num_rows($res);			 	
				  if($num>0){
					  $sno=1;
					  
					  while($rows1 = $db1->fetch_array())
					  {
							$emp_name=stripslashes($rows1['EmployeeName']);
							$emp_code=stripslashes($rows1['EmployeeCode']);
							$MobileNo=stripslashes($rows1['MobileNo']);
							$DateOfRetirement=stripslashes($rows1['DateOfRetirement']);
							$DateOfRetirement=FormatDate($DateOfRetirement);
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
						$PRODUCT_LIST.="<tr><td colspan='4' align='center'>No Employee Found.</td></tr>";
				  }
			}
		
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<a href='emp_retirement.php?showreport=".$_GET['showreport']."&Dept=".$_GET['Dept']."&deptName=".$_GET['deptName']."&Retirement=".$_GET['Retirement']."&RetiringMonth=".$_GET['RetiringMonth']."&RetiringYear=".$_GET['RetiringYear']."&RetireIn=".$_GET['RetireIn']."&Retire=".$_GET['Retire']."&page=$prevpage&max=$MAX' ><< Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='emp_retirement.php?showreport=".$_GET['showreport']."&Dept=".$_GET['Dept']."&deptName=".$_GET['deptName']."&Retirement=".$_GET['Retirement']."&RetiringMonth=".$_GET['RetiringMonth']."&RetiringYear=".$_GET['RetiringYear']."&RetireIn=".$_GET['RetireIn']."&Retire=".$_GET['Retire']."&page=$lastrow&max=$MAX'>Next>></a>";
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
					$PAGE_NAVS.=" <a href='emp_retirement.php?showreport=".$_GET['showreport']."&Dept=".$_GET['Dept']."&deptName=".$_GET['deptName']."&Retirement=".$_GET['Retirement']."&RetiringMonth=".$_GET['RetiringMonth']."&RetiringYear=".$_GET['RetiringYear']."&RetireIn=".$_GET['RetireIn']."&Retire=".$_GET['Retire']."&page=$i&max=$MAX'>$toPrint</a>";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
			$PRODUCT_LIST="<tr><td colspan='7'>No Employee Retirement Found.</td></tr>";
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