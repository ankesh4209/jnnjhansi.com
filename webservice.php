<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$json = file_get_contents('php://input');
//$json='{"action":"login","mobile":"9818247988","password":"234567"}';
//$json='{"action":"register","mobile":"9818247988","name":"hdfsjj","address":"dsfsdfsd"}';
$obj = json_decode($json);

//print_r($obj);
//die;

if($obj->action=='login') {
  $mobile=$obj->mobile;
  $password=$obj->password;
  
  $select="select * from smartcity_reg where mobile='$mobile' AND password='$password'";
  $res=$db->query($select);
  if($db->num_rows()) {
    $res=$db->fetch_array($row);
    $response['msg']='success';
    $response['user_id']=$res['id'];
    $response['username']=$res['name'];
  } else {
    $response['msg']='error';
    $response['user_id']=0;
    $response['username']='';
  }
  $json_response=json_encode($response);
}

if($obj->action=='register') {
  $mobile=$obj->mobile;
  $name=$obj->name;
  $address=$obj->address;
  $password=rand(00000,99999);
  
  $insert="insert into smartcity_reg (name,mobile,password,address) values ('$name','$mobile','$password','$address')";
  $res=$db->query($insert);
  if($res) {
    if($mobile!='') {
      //--- send sms ------
      $message1="login%20details%20for%20smart%20city.%20username%20$mobile%20password%20$password";
      $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=JNNJHS&password=JNNJHS&mobiles=$mobile&sms=$message1&senderid=JNNJHS";
      $file1=fopen("$url1","r");
      fclose("$file1");
    }
    $response['msg']='success';
  } else {
    $response['msg']='error';
  } 
  
  $json_response=json_encode($response);
}

if($obj->action=='save_comment') {
  $user_id=$obj->user_id;
   if($user_id!='') {
    $answer1=$obj->answer1;
    $answer2=$obj->answer2;
    $answer3=$obj->answer3;
    $answer4=$obj->answer4;
    $answer5=$obj->answer5;
    $answer6=$obj->answer6;
    $answer7=$obj->answer7;
    $answer8=$obj->answer8;
    $answer9=$obj->answer9;
    $answer10=$obj->answer10;
    $answer11=$obj->answer11;
    $answer12=$obj->answer12;
    $answer13=$obj->answer13;
    $answer14=$obj->answer14;
    
    $insert="insert into smartcity_comment (user_id,comment1,comment2,comment3,comment4,comment5,comment6,comment7,comment8,comment9,comment10,
    comment11,comment12,comment13,comment14) values ('$user_id','$answer1','$answer2','$answer3','$answer4','$answer5','$answer6','$answer7','$answer8'
    ,'$answer9','$answer10','$answer11','$answer12','$answer13','$answer14')";
    $res=$db->query($insert);
   $response['msg']='success';
  } else {
    $response['msg']='error';
  }
  $json_response=json_encode($response);
}


echo $json_response;
?>
         