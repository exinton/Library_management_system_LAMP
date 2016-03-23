<?php
 
   function connectMysql($dbhost,$dbuser,$dbpass,$db,$port){
   	
   $conn = mysqli_init();
      if (!$conn) {
      	die('mysqli_init failed');
      }
            
      if (!mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true)) {
      	die('Setting MYSQLI_OPT_LOCAL_INFILE failed');
      }
      
      if (!mysqli_real_connect($conn, $dbhost, $dbuser, $dbpass, $db,$port)) {
      	die('Connect Error (' . mysqli_connect_errno() . ') '
      			. mysqli_connect_error());
      }
      
      
       
      if(! $conn ) {
      	die('Could not connect: ' . mysql_error());
      }
       
   
   	return $conn;
   	   
   }
   
?>
