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

      //create book table
      $sql="create table book ( isbn varchar(13) primary key, title varchar(250) not null );";
      $retval=mysqli_query($conn, $sql);
      if(! $retval ) {
      	die('Could not enter data: ' . mysql_error());
      }
      
      echo "create book table successfully\n";
      

      //upload book table
      $sql="load data local infile '".$target_dir.$file_name."' into table book fields terminated by '\t' lines terminated by '\r\n' ignore 1 rows (isbn,@dummy,title,@dummy,@dummy,@dummy,@dummy);";     
  	  print $sql;
      $retval=mysqli_query($conn, $sql);
 	  if(! $retval ) {
  	  	die('Could not enter data: ' . mysql_error());
  	  } 	   
  	  echo "upload book table data successfully\n";
  	 
  	  //create book_authors table
  	  $sql="create table book_authors ( isbn varchar(13) not null, author_name varchar(100) not null, primary key(isbn,author_name), foreign key(isbn) references book(isbn));";
  	  mysqli_query($conn, $sql);
  	  if(! $retval ) {
  	  	die('Could not enter data: ' . mysql_error());
  	  }
  	  echo "create book_author table successfully\n";
  	  
  	  //upload book_author table
  	  $sql="load data local infile '".$target_dir.$file_name."' into table book_authors fields terminated by '\t' lines terminated by '\r\n' ignore 1 rows (isbn,@dummy,@dummy,author_name,@dummy,@dummy,@dummy);";
  	  $retval=mysqli_query($conn, $sql);
	
  	  if(! $retval ) {
  	  	die('Could not enter data: ' . mysql_error());
  	  }
  	  
  	  echo "upload table book_authors data successfully\n";
	   
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
