<html>

<head>

<title>Library book checkout!</title>

</head>
<body>


<h2>
check out book!
</h2>

 <form action="" method="post">
  ISBN: <input type="text" name="isbn"><br>
  Book Title: <input type="text" name="title"><br>
  Branch Id: <input type="text" name="branch"><br>
  Card number: <input type="text" name="card_id"><br>
  <input type="submit" value="Submit">
</form> 

<?php
//define variables
include 'check_data.php';
include 'configurationVars.php';
include 'connect.php';
$title=$isbn=$borrower=$branch="";

if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$isbn=$_POST["isbn"];
	$title=$_POST["title"];
	$branch=$_POST["branch"];
	$card=$_POST["card_id"];

	$date_out=date("Y-m-d");
	$date_due=7;
	
	echo $loanid."\n",$date_out,$date_due;
	
	//

	print "test";
	echo $authors.$title.$isbn;
	$conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);	
	$check_result=check_borrower_quota($card,$conn);
	echo $check_result;
	
	if(check_book_availability($isbn,$branch,$conn)==no_borrowed_branch($isbn,$branch,$conn)){
		die('ran out of the book'.$isbn);
		
	}
	
	if($check_result==true){
		
		reserve_book($isbn,$branch,$card,$conn);
		
	}
	else 
		die('Could not borrow more than 3 books ');
	
}





?>




</body>
</html>