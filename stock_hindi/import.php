<?

$databasehost = "localhost";
$databasename = "jnnjhrqb_procurement";
$databasetable = "items";
$databaseusername ="jnnjhrqb_procur";
$databasepassword = "procurement#123";
$fieldseparator = ",";
$lineseparator = "\n";
//$csvfile = "G:\final1.csv";
//$csvfile = "C:/wamp/www/rnd/items.csv";
$csvfile = "/home/jnnjhrqb/public_html/eprocurement/items.csv";
$addauto = 0;
$save = 0;
$outputfile = "output.sql";


if(!file_exists($csvfile)) {
	echo "File not found. Make sure you specified the correct path.\n";
	exit;
}

$file = fopen($csvfile,"r");

if(!$file) {
	echo "Error opening data file.\n";
	exit;
}

$size = filesize($csvfile);

if(!$size) {
	echo "File is empty.\n";
	exit;
}

$csvcontent = fread($file,$size);

fclose($file);

$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());

$lines = 0;
$queries = "";
$linearray = array();
$i=1;
foreach(@split($lineseparator,$csvcontent) as $line) {

	$lines++;

	$line = trim($line," \t");
	
	$line = str_replace("\r","",$line);
	
	/************************************************************************************************************
	This line escapes the special character. remove it if entries are already escaped in the csv file
	************************************************************************************************************/
	$line = str_replace("'","\'",$line);
	/***********************************************************************************************************/
	
	$linearray = explode($fieldseparator,$line);
	echo '<pre>';
	print_r($linearray);
	
	$linemysql = implode("','",$linearray);
	//echo $linemysql;
	
		echo"<br>".$query="insert into items (ItemType,ItemName) values ('2','$linearray[0]')";


	
//$update="update complainant set c_contno='0',c_mode='OTHERS' where c_id in (1177,1178,1179,1180,1181,1182,1183,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,1194,1195,1196,1197,1198,1199,1200,1201,1202,1203,1204,1205,1206)";

/*update complainant1 set c_contno='0',c_mode='OTHERS' where c_id in (1177,1178,1179,1180,1181,1182,1183,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,1194,1195,1196,1197,1198,1199,1200,1201,1202,1203,1204,1205,1206)*/
	//	echo$query = "insert into $databasetable values('$linemysql');";
	
	$queries .= $query . "\n";

	@mysql_query($query);
	$i++;
	if($i==387)
		break;
}
//echo $queries;
@mysql_close($con);

if($save) {
	
	if(!is_writable($outputfile)) {
		echo "File is not writable, check permissions.\n";
	}
	
	else {
		$file2 = fopen($outputfile,"w");
		
		if(!$file2) {
			echo "Error writing to the output file.\n";
		}
		else {
			fwrite($file2,$queries);
			fclose($file2);
		}
	}
	
}

echo "Found a total of $lines records in this csv file.\n";

?>




<? 

/*
include 'connect.php'; 


$filename='temp.csv'; 

$handle = fopen("$filename", "r"); 
while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) 
{ 
$import="INSERT into inventory(tool_id,process,manufacturer,model,description) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]');";
 mysql_query($import) or die("mysql_error()"); 
print $import."<br>"; 
} 
fclose($handle); 
print "Import done"; */

?> 


