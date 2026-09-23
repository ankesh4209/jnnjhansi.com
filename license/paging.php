<?php
/*********************************************************************
// this functuion is for showing page number in the page.			// 
// it takes parameter as											//																
//																	//
//	$page //current page showing									//
//	$numofpages // total number of pages							//
//	$pagelimit // how many page to be show at a time				//
//	$link // link for paging. excluding the page variable 			//
//  it finaly return the formated string tha can be used directly in// 
//  the pages.														// 
// Save this file as paging.php																	// 
*********************************************************************/
function paging($page,$numofpages,$pagelimit,$link)
{

	$tmplink = explode(".php",$link);
	if($tmplink[1] != '')
	{
		$link = $link."&";
	}
	else
	{
		$link = $link."?";
	}
	$str_to_return = "";
	
		$pageprev = $page-1;
		if($page > 1)
		{
			$str_to_return .= "<a href=\"".$link."pg=".$pageprev."\" class=\"get\" >Previous</a>&nbsp;";
		}
		else
		{
		//	$str_to_return .= "<font color=\"GRAY\">Previous</font>&nbsp;";
		}


	if($pagelimit < $page)
	{
  		$start_page = $page-($pagelimit-1);
	}
	else
	{  
		$start_page = 1;
	}	   
	$temp_str = "";
	for($i=1;($i<=$pagelimit && $i<=$numofpages );$i++)
	{
		if($page == $start_page)
		{ 
			$temp_str .= "<font color=\"GRAY\">".$start_page."</font>&nbsp;";
		}
		else 
		{
			$temp_str .= "<a href=\"".$link."pg=".$start_page."\" class=\"get\">".$start_page."</a>&nbsp;"; 
		}
		$start_page++;
	}
	$str_to_return .= $temp_str;
	if($page < $numofpages)
	{ 
		$pagenext = ($page + 1); 
		$str_to_return .="<a href=\"".$link."pg=".$pagenext."\" class=\"get\">Next</a>";
	}
	//else
	//{
	//	$str_to_return .="<font color=\"GRAY\">NEXT</font>";
	//}

	return $str_to_return;
}
?>