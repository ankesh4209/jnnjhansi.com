<?php
//--------------------------------------------------------------
// Global Configuration File
//--------------------------------------------------------------

// SOME GUIDELINES:
// Make sure that all files/dirs have a valid permissions.
// Right permissions should be for dirs 0755 and for files 0644.
// Please mention in the below line which dirs/files must have writeable by webserver.
// You should also write some description here about dirs/files.
// This may be require to other developers who will might have to work on this project.

// <MENTION HERE DIRS/FILES NAME WHICH MUST HAVE WRITEABLE BY WEBSERVER>


       $HOST_NAME                        = $_SERVER['SERVER_NAME'];
       $DOCUMENT_ROOT                    = $_SERVER['DOCUMENT_ROOT'];

        //--------------------------------------------------------------
        // DATABASE PARAMETERS
        //--------------------------------------------------------------

        $DB_HOST                        = "localhost";                  // Database Host Server
        $DB_USERNAME                    = "jnnjhrqb_pis";              // Database Username
        $DB_PASSWORD                    = "pis@1234";              // Password for the Db User
        $DB_NAME                        = "jnnjhrqb_pis";              // Database name
        $DB_REPORT_ERROR                = false;                        // To Report Error
        $DB_PERSISTENT_CONN             = false;                        // If Db Connection to be persistent
        //Application directory Path of the app from Virtual root
        //$MAP_VROOT_PATH                 = "";

        $TEMPLATE_DIR                   = "templates";

?>
