<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


ViewTenders($db);
GetNotices($db);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/tenders.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function ViewTenders($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$Pdf_Id,$Pdf_Name,$tender_view,$TenderDate,$StartDate,$EndDate,$StatusPill,$filter_status,$pages;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/tender_infoGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = isset($_GET['page']) ? max(0, (int)$_GET['page']) : 0;
	 $MAX = 10;
	 $lastrow = $MAX + $page;
	 
	 $count = "select count(Pdf_Id) as total from pdffiles where Status='1'";
     $db->query($count);
	 $row = $db->fetch_assoc();
     $TOTAL_RECORDSET = (int)$row['total'];
  
     $query = "select * from pdffiles where Status='1' order by Pdf_Id DESC limit $page, $MAX";
    
     $db->query($query);
		if($db->num_rows())
		{
		  $slno = $page + 1;
			while($rows = $db->fetch_array())
			{
			  $Pdf_Id = $rows['Pdf_Id'];
			  $Pdf_Name = $rows['Pdf_Desc'];
			  $TenderDate = !empty($rows['AddedDate']) ? $rows['AddedDate'] : '—';
			  $StartDate = !empty($rows['StartDate']) ? $rows['StartDate'] : (!empty($rows['AddedDate']) ? date('d-m-Y', strtotime($rows['AddedDate'])) : '—');
			  $EndDate = !empty($rows['EndDate']) ? $rows['EndDate'] : '—';

			  // Status logic
			  $now = time();
			  $startTs = strtotime($StartDate);
			  $endTs = strtotime($EndDate);

			  if ($endTs && $endTs < $now && date('Y-m-d', $endTs) < date('Y-m-d', $now)) {
			      $filter_status = 'closed';
			      $StatusPill = '<span class="status-pill" style="background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; font-weight:700;">Closed</span>';
			  } elseif ($startTs && $startTs > $now && date('Y-m-d', $startTs) > date('Y-m-d', $now)) {
			      $filter_status = 'upcoming';
			      $StatusPill = '<span class="status-pill" style="background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; font-weight:700;">Upcoming</span>';
			  } else {
			      $filter_status = 'open';
			      $StatusPill = '<span class="status-pill status-open" style="background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; font-weight:700;">Open</span>';
			  }

			  $Pdf_File = htmlspecialchars($rows['Pdf_Name']);
			  $tender_view = "<a href='docs/$Pdf_File' style='color:#FFFFFF; text-decoration:none;' target='_blank'>Download</a>";
			
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST .= $S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		    if($page > 0)
			{	
			    $prevpage = max(0, $page - $MAX);
				$PREV_PAGE_LINK = "<a href='tenders.php?page=$prevpage&max=$MAX' style='color:var(--primary); font-weight:600; text-decoration:none;'>&laquo; Prev</a>";
			} else {
			    $PREV_PAGE_LINK = "";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	
			    $NEXT_PAGE_LINK = "<a href='tenders.php?page=$lastrow&max=$MAX' style='color:var(--primary); font-weight:600; text-decoration:none;'>Next &raquo;</a>";
			} else {
			    $NEXT_PAGE_LINK = "";
			}
							
			$PAGE_NAVS = "";
			for($i = 0, $toPrint = 1; $i < $TOTAL_RECORDSET; $i += $MAX, $toPrint++)
			{	
                if ($i == $page)
				{	
                    $PAGE_NAVS .= " <b style='color:var(--accent); font-size:14px; padding:2px 8px; border-radius:4px; background:rgba(244,119,33,0.1);'>".$toPrint."</b> | ";
					$CURRENT_PAGE_NO = $toPrint;
				}
				else
				{	
                    $PAGE_NAVS .= " <a href='tenders.php?page=$i&max=$MAX' style='color:var(--primary); text-decoration:none; padding:2px 6px;'>$toPrint</a> |";
				}
				$TOTAL_PAGES = $toPrint;
			}
		    $pages = "Pages:";
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No tender Found</td></tr>";
    }
   
  return 1;
 }

function GetNotices($db)
{
   global $Notice_Id,$NoticeName,$NoticeList,$cid,$notice_view;
   $sql="select * from notice where Status='1' order by Pdf_Id DESC";
   $res=$db->query($sql);
    $i=0;
	while($rows = $db->fetch_array())
	{
		$Notice_Id=$rows['Pdf_Id'];
	 
	   $NoticeName=$rows['Pdf_Desc'];

	   if($_SERVER['SERVER_NAME']=='localhost')
		{
			$notice_view.="<a href='docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$NoticeName."</a>::&nbsp;";
		}
		else
		{
			$notice_view.="<a href='/docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$NoticeName."</a>::&nbsp;";
		}
	  $i++;
	}			
}
?>
