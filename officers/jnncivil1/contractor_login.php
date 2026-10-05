<?php 
session_start();	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if(isset($_POST['submit']) && $_POST['submit']=='LogIn'){
	  $password = $_POST["password"];
      $Email = $_POST["Email"];
  // echo authenticateUser($password, $Email, $db);
	$result=authenticateUser($password, $Email, $db);
		if ($result==5)
	   {	
        echo "<script type='text/javascript'>
                <!--
                  window.location = 'profile.php'
                //-->
                </script>
              ";
		 }else if($result==1){
		 
            $PROPMT = "Your account has been cancelled.Please contact Nagar Nigam Jhansi";
         }else if($result==2){
		 
            $PROPMT = "Your account is not activated.";
         }else if($result==3){
		 $PROPMT = "Password do not match.";
            
         }else if($result==4){
		 $PROPMT = "Invalid username/password.";
            
         }
}

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/contractor_login.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();


function authenticateUser($password, $Email, $db)
{	
	GLOBAL $PROMPT;
		
  $query="select * from contractors where Email = '$Email'";	 
   $db->query($query);
	if ($db->num_rows())
	 {	
		 $row = $db->fetch_array();
	     $v_password = $row['password'];
			 
				if (strcmp ($password, $v_password )==0 ) // Binary Comparision for Case Sensitivity
				{	
			  	    if($row['Status']=='Approved'){
						$Email= $row['Email'];
						$_SESSION['User_Email']= $Email;
						$_SESSION['ContractorId']= $row['ContractorId'];
						$_SESSION['hashiyatkishreni']= $row['hashiyatkishreni'];
						return 5;
					}else if($row['Status']=='Cancelled'){
						$PROMPT = "Your account has been cancelled.Please contact Nagar Nigam Jhansi";
					    $PROMPT_CLASS = "error";
					    return 1;
					}else{
						$PROMPT = "Your account is not activated.";
					    $PROMPT_CLASS = "error";
					    return 2;
					}
				}
				else
				{	//Not Authenticated
					$PROMPT = "Authentication failed";
					$PROMPT_CLASS = "error";
					return 3;
				}
			
	}
	else
	{	//Not Authenticated
		$PROMPT = "Authentication failed";
		$PROMPT_CLASS = "error";
		return 4;
	}
}

?>
