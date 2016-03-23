

<?php
include 'configurationVars.php';

include 'connect.php';
  if(isset($_FILES['image'])){
	$errors= array();
	$file_name = $_FILES['image']['name'];
	$file_size =$_FILES['image']['size'];
	$file_tmp =$_FILES['image']['tmp_name'];
	$file_type=$_FILES['image']['type'];
	$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

	$expensions= array("csv","txt","xls");

	if(in_array($file_ext,$expensions)=== false){
		$errors[]="extension not allowed, please choose a csv or txt file.";
	}


	if(empty($errors)==true){
		move_uploaded_file($file_tmp,$target_dir.$file_name);

		echo "load Success!";
	}else{
		print_r($errors);
	}
	echo "test";

	$conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);
      
      //create table branch
      $createbooktable="create table library_branch ( branch_id varchar(3) not null, branch_name varchar(50) not null, address varchar(100) not null,primary key(branch_id));";
      $retval=mysqli_query($conn, $createbooktable);
      if(! $retval ) {
      	die('Could not enter data: ' . mysql_error());
      }
      
      echo "creat table branch_library data successfully\n";
      
      $sql="load data local infile '".$target_dir.$file_name."' into table library_branch fields terminated by '\t' lines terminated by '\n' ignore 1 rows (branch_id,branch_name,address);";
      ;
  	  $retval=mysqli_query($conn, $sql);
  	  
  	  if(! $retval ) {
  	  	die('Could not enter data: ' . mysql_error());
  	  }
  	   
  	  echo "upload table branch_library data successfully\n";
  	 
  	  	  
  	   
  	  mysqli_close($conn);

  }
?>
<html>
   <body>
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="submit"/>
      </form>
      
   </body>
</html>
