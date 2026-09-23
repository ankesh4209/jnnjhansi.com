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
		$this->password = $dbpass;
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
		/* Connecting to Db Host */
		if ($this->persistent) {
			$this->conn = mysql_pconnect($this->host, $this->user, $this->password); 
		} else {
			$this->conn = mysql_connect($this->host, $this->user, $this->password); 
		  }             
                   
        	if (!$this->conn) {
			print "Cannot connect to Database Host: ". $this->host;
			return false; 
		} 
                   
	        /* Select DB */ 
		if (@!mysql_select_db($this->database, $this->conn)) {
			print "Cannot select Database: ". $this->database;
			return false; 
		} 
		return true; 
	}  
	
	/* Close the connection */ 
               
	function close() {
		return (@mysql_close($this->conn)); 
	} 
    
	/* Report Error */ 
                 
	function error() {
		if ($this->error_reporting) {
			return (mysql_error()) ; 
		}           
	} 
	
	/* Execute Query */

	function query($sql) {
		$this->result = @mysql_query($sql, $this->conn); 
		return($this->result != false);         
	} 
	
	/* Affected Rows for Updates & Deletes */

	function affected_rows() {
		return(@mysql_affected_rows($this->conn)); 
	} 
	
	/* Total Rows returned by Query */

	function num_rows() {
		return(@mysql_num_rows($this->result)); 
	} 
	
	/* Fetch Resultset as Object */

	function fetch_object() {
		return(@mysql_fetch_object($this->result)); 
	} 

	/* Fetch Resultset as Array */

	function fetch_array() {
		return(@mysql_fetch_array($this->result)); 
	} 
	
	/* Fetch Resultset as Associate Array only */

	function fetch_assoc() {
		return(@mysql_fetch_assoc($this->result)); 
	} 
      
	/* Free Resultset */
	function free_result() {
		return(@mysql_free_result($this->result)); 
	}  
	
	/* Free Resultset */
	function insert_id() {
		return(@mysql_insert_id($this->conn)); 
	} 
} 
?>
