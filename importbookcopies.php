

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
 
      //create table book_copies
      $createbooktable="create table book_copies ( book_id varchar(20) not null, branch_id varchar(3) not null, no_of_copies int(3) not null,primary key(book_id,branch_id), foreign key(book_id) references book(isbn), foreign key(branch_id) references library_branch(branch_id));";
      $retval=mysqli_query($conn, $createbooktable);
      if(! $retval ) {
      	die('Could not enter data: ' . mysql_error());
      }
      
      echo "creat table book_copies data successfully\n";
      
      $sql="load data local infile '".$target_dir.$file_name."' into table book_copies fields terminated by '\t' lines terminated by '\n' ignore 1 rows (book_id,branch_id,no_of_copies);";
  	  $retval=mysqli_query($conn, $sql);
  	  
  	  if(! $retval ) {
  	  	die('Could not enter data: ' . mysql_error());
  	  }
  	   
  	  echo "upload table book_copies data successfully\n";
  	 
  	  	  
  	   
  	  mysqli_close($conn);
}
?>
<html>
   <body>
      
      <form action="" method="post" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="submit"/>
      </form>
      
   </body>
</html>
