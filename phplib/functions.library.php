<?php
error_reporting(0);
#-------------------------------------------------------------
// Function	: ReadTemplate
// Description	: Reads Template File and return the Content of the File 

function ReadTemplate($fileName) {
	$fd = fopen($fileName, "r");
	return @fread($fd, filesize ($fileName));
}

#-------------------------------------------------------------
// Function	: RestoreData
// Description	: Restore Posted Data to its Origianl Form. Must be called before sending back posted data to the browser
function RestoreData() {
	global $HTTP_POST_VARS, $HTTP_GET_VARS;
	foreach($HTTP_POST_VARS as $var=>$value) {
	 	global $$var;
		$$var = stripslashes($value);
	}
	foreach($HTTP_GET_VARS as $var=>$value) {
		global $$var;
		$$var = stripslashes($value);
	}

}

#-------------------------------------------------------------
// Function	: ReplaceContent
// Description	: Replace Content in Templates with Equivalent Variables

function ReplaceContent($VarList) {

	for($i=0; $i<count($VarList); $i++) {

		global ${$VarList[$i]}; 

		${$VarList[$i]} = preg_replace_callback("/__(\w+)__/",
		function ($matches) {		
			return isset($GLOBALS[$matches[1]]) ? $GLOBALS[$matches[1]] : '';
		} ,
		${$VarList[$i]}
	);
	}
	//exit();
	return 1;
	// For Future Refrence :  $RIGHT_HOME_CONTENT=preg_replace("/__(\w+)__/e","$$1",$RIGHT_HOME_CONTENT);
}

#-------------------------------------------------------------
// Function	: placeScripts
// Description	: Replace Content in Templates with Equivalent Variables

function placeScripts($ScriptList) {
	global $SCRIPTS;
	$SCRIPTS = "";
	for($i=0; $i<count($ScriptList); $i++) {
		$SCRIPTS .= "<script language=JavaScript src=\"".$ScriptList[$i]."\"></script>\n";
	}
	return 1;
}


function paginate($limit=10, $tot_rows)
{
	global $TOTAL_PAGES, $pagination;
	$numrows = $tot_rows;
	
	if($numrows > $limit)
	{
		if(isset($_GET['page']))
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = 0;
		}
		
		$currpage = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'].$pagination;
		$currpage = str_replace("&page=".$page,"",$currpage);

		if($page == 0)
		{
			//
		}
		else
		{
			$pageprev = $page - 1;
			$darkpink .= "<a class=\"red_text_link\" href=\"" . $currpage . "&page=". $pageprev . "\">&laquo;PREV</a>";
		}

		$numofpages = ceil($numrows / $limit);
		$TOTAL_PAGES = $numofpages;
		$range = 10;
		$lrange = max(1, $page-(($range-1)/2));
		$rrange = min($numofpages, $page+(($range-1)/2));
			
		if(($rrange - $lrange) < ($range - 1))
		{
			if($lrange == 1)
			{
				$rrange = min($lrange + ($range-1), $numofpages);
			}
			else
			{
				$lrange = max($rrange - ($range-1), 0);
			}
		}
		
		if($lrange > 4)
		{
			$secfirst = 2;
			$darkpink .= "&nbsp;<a class='red_text_link' href='$currpage&page=1'>1</a>&nbsp;";
			$darkpink .= "<a class='red_text_link' href='$currpage&page=$secfirst'>$secfirst</a>&nbsp;";
			$darkpink .= " .. ";
		}
		else
		{
			$darkpink .= " &nbsp;";
		}

		for($i = 1; $i <= $numofpages; $i++)
		{
			if(($i-1) == $page)
			{
				$darkpink .= "<font color=\"#888888\" size='2'> ".($i)." </font>";
			}
			else
			{
				if($lrange <= $i && $i <= $rrange)
				{
					$darkpink .= " <a  class='red_text_link' href=\"".$currpage."&page=".($i-1)."\">" . $i . "</a> ";
				}
			}
		}
		
		if($rrange < $numofpages-3)
		{
			$seclast = $numofpages -1;
			$darkpink .= " .. ";
			$darkpink .= "<a  class='red_text_link' href='$currpage&page=$seclast'>$seclast</a>&nbsp;";
			$darkpink .= "<a  class='red_text_link' href='$currpage&page=$numofpages'>$numofpages</a>&nbsp;";
		}
		else
		{
			$darkpink .= " &nbsp;&nbsp; ";
		}

		if(($numrows - ($limit * $page)) > 1)
		{
			$numrows - ($limit * $page);
			$pagenext = $page + 1;
			$darkpink .= "<a class='red_text_link' href=\"". $currpage . "&page=" . $pagenext . "\">NEXT&raquo;</a>";
		}
		else
		{
			//	$darkpink .= "<span class=\"txt\">NEXT &gt;</span>";
		}
	}
	return $darkpink;
}


function GetMainCategory($db,$db1)
{
    global $maincategory,$category;
       $maincategory="";
    $sql="select * from page where status='1' ";
    $res=$db->query($sql);
    while($rows=$db->fetch_array($res))
    {
      $maincategory.="<li>";
      $cname=$rows['p_name'];
      $pid=$rows['p_id'];
      $maincategory.="<a href='pagecontent.php?pid=$pid'  rel='$cname'>$cname</a>";
      $maincategory.="</li>";
      
      $sql1="select * from  subpage where P_id='$pid' ORDER BY subp_name ASC ";   
      $res1=$db1->query($sql1);
      $category.="<div id='$cname' class='dropmenudiv_b' style='width: 150px;'>";
       while($rows1=$db1->fetch_array($res1))
        {
          $subpname=$rows1['subp_name'];
          $subpid=$rows1['subp_id'];
          $category.="<a href='subpagecontent.php?subpid=$subpid'>$subpname</a>";
        }
          $category.="</div>";
   }
}


function GetnewinformaionHome($db)
{
    global $newsinfo;
   $i=0;
    $sql="select * from tbl_news where n_status='1' order by n_id limit 0,2";
    $res=$db->query($sql);
    
    while($rows=$db->fetch_array($res))
    { 
      $title=$rows['n_title'];
      $text=substr($rows['n_text'],0,140);
      $nid=$rows['n_id'];
      $newsinfo.="<tr><td class='newsa'>$text<a href='newsmore.php?nid=$nid'> read more</a>...</td></tr>";
      $i++;
   }
}

function GetEventsinformaion($db)
{
    global $evetsinfo;
     $i=0;
    $sql="select * from tbl_event where e_status='1' ";
    $res=$db->query($sql);
    
    while($rows=$db->fetch_array($res))
    {
      $evetsinfo=$rows['events_text'];
      $nid=$rows['e_id'];
   }
}


?>

<?php 
  
 if ($_SESSION['username']=="")
  {	
    $loginname="Guest";
    $loglink="<a href='login.php'>Login</a>";
  }
 else
  {
    $loginname=$_SESSION['username'];
    $loglink="<a href='logout.php'>Logout</a>";
  }
?>

