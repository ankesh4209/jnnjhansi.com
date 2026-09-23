<?php

 $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=UPHDBLKO&password=UPHDBLKO&mobiles=9818247988&sms=test&senderid=UPHDBL";
	$file1=fopen("$url1","r");
    fclose("$file1");
 
 $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=JNNJHS&password=JNNJHS&senderid=JNNJHS&mobiles=9818247988&sms=test";
  $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$url1);       
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_exec($curl_handle);
    curl_close($curl_handle);
    echo"msg send";   
/* 
$xml_data ="<smslist><sms><user>CROPSOFT</user><password>D@ve23</password><message>test</message><mobiles>9818247988</mobiles><senderid>JNNJHS</senderid><cdmasenderid>00201009546310</cdmasenderid><accountusagetypeid>1</accountusagetypeid></sms></smslist>"; 
 
$URL = "http://trans.cropsoft.co.in/reseller/sendsms.jsp?";
 
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
 
print_r($output);  */
                   
?>