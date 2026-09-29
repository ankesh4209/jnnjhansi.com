<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


ViewAdministration($db);
GetNotices($db);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/administration.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function ViewAdministration($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$OfficerName,$OfficerPhoto,$OffNoLink,$Officer_Id,$Designation,$pages;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/administration_infoGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = isset($_GET['page']) ? max(0, (int)$_GET['page']) : 0;
	 $MAX = 10;
	 $lastrow = $MAX + $page;
	 
	 $count = "select count(Officer_Id) as total from officers where Status='1'";
     $db->query($count);
	 $row = $db->fetch_assoc();
     $TOTAL_RECORDSET = (int)$row['total'];
  
     $query = "select * from officers where Status='1' order by Officer_Id ASC limit $page, $MAX";
    
     $db->query($query);
		if($db->num_rows())
		{
		  $slno = $page + 1;
			while($rows = $db->fetch_array())
			{
			  $Officer_Id = (int)$rows['Officer_Id'];
			  $OfficerName = trim($rows['FirstName']." ".$rows['LastName']);
			  $Designation = trim($rows['Designation']);
			  $OffNo = trim($rows['OfficeNo'] ?? '');

			  // 1. Officer Photo / Avatar
			  $photoFile = trim($rows['Photo'] ?? '');
			  $foundPhoto = '';
			  if (!empty($photoFile)) {
			      if (file_exists("c_images/$photoFile")) {
			          $foundPhoto = "c_images/$photoFile";
			      } elseif (file_exists("c_images/thumbs/$photoFile")) {
			          $foundPhoto = "c_images/thumbs/$photoFile";
			      } elseif (file_exists("images/officers/$photoFile")) {
			          $foundPhoto = "images/officers/$photoFile";
			      } elseif (file_exists("pic/$photoFile")) {
			          $foundPhoto = "pic/$photoFile";
			      }
			  }

			  if (!empty($foundPhoto)) {
			      $OfficerPhoto = "<img src='$foundPhoto' alt='$OfficerName' style='width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid var(--accent); box-shadow:0 3px 8px rgba(18,54,90,0.18); display:block; margin:0 auto;'>";
			  } else {
			      // Modern initials badge
			      $cleanName = preg_replace('/^(Mr\.|Mrs\.|Ms\.|Dr\.|Shri|Smt\.)\s+/i', '', $rows['FirstName']);
			      $parts = preg_split('/\s+/', trim($cleanName));
			      $initials = '';
			      if (!empty($parts[0])) $initials .= strtoupper(substr($parts[0], 0, 1));
			      if (!empty($parts[1])) $initials .= strtoupper(substr($parts[1], 0, 1));
			      if (empty($initials)) $initials = 'JN';

			      $colors = [
			          ['#1E3A8A', '#2563EB'],
			          ['#0F766E', '#0D9488'],
			          ['#9A3412', '#EA580C'],
			          ['#6B21A8', '#9333EA'],
			          ['#1E293B', '#475569'],
			          ['#065F46', '#059669'],
			          ['#831843', '#DB2777'],
			          ['#155E75', '#0284C7']
			      ];
			      $colorPair = $colors[$Officer_Id % count($colors)];

			      $OfficerPhoto = "<div style='width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg, {$colorPair[0]} 0%, {$colorPair[1]} 100%); color:#FFFFFF; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:15px; letter-spacing:0.5px; border:2px solid #FFFFFF; box-shadow:0 3px 8px rgba(0,0,0,0.12); margin:0 auto;' title='$OfficerName'>$initials</div>";
			  }

			  // 2. Contact No link
			  if (!empty($OffNo) && $OffNo !== '.' && $OffNo !== '-' && strtolower($OffNo) !== 'null') {
			      $OffNoLink = "<a href='tel:$OffNo' style='display:inline-flex; align-items:center; gap:6px; background:#ECFDF5; color:#065F46; padding:6px 14px; border-radius:20px; font-weight:700; font-size:13px; text-decoration:none; border:1px solid #A7F3D0; transition:all 0.2s;' title='Call $OfficerName'><svg width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z'></path></svg> $OffNo</a>";
			  } else {
			      $OffNoLink = "<span style='color:var(--text-muted); font-size:13px;'>—</span>";
			  }
			 
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST .= $S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		    if($page > 0)
			{	
			    $prevpage = max(0, $page - $MAX);
				$PREV_PAGE_LINK = "<a href='administration.php?page=$prevpage&max=$MAX' style='color:var(--primary); font-weight:600; text-decoration:none;'>&laquo; Prev</a>";
			} else {
			    $PREV_PAGE_LINK = "";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	
			    $NEXT_PAGE_LINK = "<a href='administration.php?page=$lastrow&max=$MAX' style='color:var(--primary); font-weight:600; text-decoration:none;'>Next &raquo;</a>";
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
                    $PAGE_NAVS .= " <a href='administration.php?page=$i&max=$MAX' style='color:var(--primary); text-decoration:none; padding:2px 6px;'>$toPrint</a> |";
				}
				$TOTAL_PAGES = $toPrint;
			}
		    $pages = "Pages:";
		}
		else
		{
            $PRODUCT_LIST = "<tr><td colspan='5' style='text-align:center; padding:24px; color:var(--text-muted);'>No Officer(s) Found</td></tr>";
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
