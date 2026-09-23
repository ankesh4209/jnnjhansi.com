<?php

Class DbConnect 
{	
	/* Member Variables */

	var $host = ''; 
	var $user = ''; 
	var $password = ''; 
	var $database = ''; 
	var $persistent = false; 
	var $conn = NULL; 
	var $result = false; 
	var $error_reporting = false; 
	
	/* Constructor */

	function __construct ($dbhost,$dbuser,$dbpass,$dbname, $error_reporting=true, $persistent=false) {
		$this->host = $dbhost; 
		$this->user = $dbuser; 
        $this->password =$dbpass; 
		$this->database = $dbname; 
    $this->persistent = $persistent; 
    $this->error_reporting = $error_reporting; 
	} 

	function DbConnect ($dbhost,$dbuser,$dbpass,$dbname, $error_reporting=true, $persistent=false) {
		$this->__construct($dbhost, $dbuser, $dbpass, $dbname, $error_reporting, $persistent);
	}
	
	/* Member Functions */

	/* Open Database Connection */

	function open() { 
	
		$this->conn = mysqli_connect($this->host, $this->user, $this->password,$this->database);            
                  
        	if (!$this->conn) {
			print "Cannot connect to Database Host: ". $this->host;
			return false; 
		} 
                   
	        /* Select DB */ 
		 if(!mysqli_select_db($this->conn,$this->database)) {
		 	print "Cannot select Database: ". $this->database;
		 	return false; 
		 } 
		return true; 
	}  
	
	/* Close the connection */ 
               
	function close() {
		if ($this->conn) {
			return (mysqli_close($this->conn)); 
		}
		return true;
	} 
    
	/* Report Error */ 
                 
	function error() {
		if ($this->error_reporting && $this->conn) {
			return (mysqli_error($this->conn)) ; 
		}           
	} 
	
	/* Execute Query */

	function query($sql) {
		$this->result = mysqli_query($this->conn,$sql); 
		return($this->result != false);         
	} 
	
	/* Affected Rows for Updates & Deletes */

	function affected_rows() {
		return(@mysqli_affected_rows($this->conn)); 
	} 
	
	/* Total Rows returned by Query */

	function num_rows() {
		return(@mysqli_num_rows($this->result)); 
	} 
	
	/* Fetch Resultset as Object */

	function fetch_object() {
		return(@mysqli_fetch_object($this->result)); 
	} 

	/* Fetch Resultset as Array */

	function fetch_array() {
		return(@mysqli_fetch_array($this->result)); 
	} 
	
	/* Fetch Resultset as Associate Array only */

	function fetch_assoc() {
		return(@mysqli_fetch_assoc($this->result)); 
	} 
      
	/* Free Resultset */
	function free_result() {
		return(@mysqli_free_result($this->result)); 
	}  
	
	/* Free Resultset */
	function insert_id() {
		return(@mysqli_insert_id($this->conn)); 
	} 
} 
?>
