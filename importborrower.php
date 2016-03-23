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


	$conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);
	
	//create borrowers table
	$sql="create table borrower ( card_no varchar(10) primary key,isbn varchar(13) not null,fname varchar(20) not null,lname varchar(20) not null,address varchar(50) , phone varchar(15) );";
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('Could not enter data: ' . mysql_error());
	}
	
	echo "create borrower table successfully\n";
	
	 $sql="load data local infile '".$target_dir.$file_name."' into table borrower fields terminated by ',' lines terminated by '\r\n' ignore 1 rows (card_no,isbn,fname,lname,@dummy,address,@dummy,@dummy,phone);";
	 $retval=mysqli_query($conn, $sql);
	 if(! $retval ) {
	 	die('Could not enter data: ' . mysql_error());
	 }
	 
	 echo "create borrower table successfully\n";

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