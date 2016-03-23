<html>

<head>

<title>Library book checkin!</title>

</head>
<body>
<h2>
check in book!
</h2>

 <form action="" method="post">
  Book: <input type="text" name="isbn"><br>
  Borrower: <input type="text" name="borrower"><br>
  Card number: <input type="text" name="card_id"><br>
  first name: <input type="text" name="fname"><br>
  last name: <input type="text" name="lname"><br>
  <input type="submit" name="checkin-submit" value="Submit">
</form> 


<?php
//define variables
include 'check_data.php';
include 'configurationVars.php';
include 'connect.php';
$conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);

if(!empty($_POST['checkin-submit'])){
	$borrower=$isbn=$card_id="";
	$borrower=$_POST["borrower"];
	$isbn=$_POST["isbn"];
	$card_id=$_POST["card_id"];
	$fname=$_POST["fname"];
	$lname=$_POST["lname"];
	
	$result=check_in_list($isbn,$card,$borrower,$fname,$lname,$conn);
	
	while($row=mysqli_fetch_assoc($result)) {
		echo "book ID :{$row['isbn']}  <br> ".
				"branch ID : {$row['branch_id']} <br> ".
				"card no : {$row['card_no']} <br> ".
				"date_out : {$row['date_out']} <br> ".
				"date_in : {$row['date_in']} <br> ".
				"first name : {$row['fname']} <br> ".
				"--------------------------------<br>";

		session_start();
		$_SESSION['isbn']=$row['isbn'];
		$_SESSION['branch_id']=$row['branch_id'];
		$_SESSION['card']=$row['card_no'];

		?>
		<form action='' method='POST'>
		checkin date  <input type='date' id='date' name='date' /><br/>
		<input type="submit" name="date-submit" value="Submit">
		</form>
		<?php

		
	
//select bl.loan_id ,bl.isbn,bl.card_no,br.fname, br.lname from book_loans bl,borrower br  where  bl.card_no=br.card_no; 
	}	
}

if(!empty($_POST['date-submit'])){

	session_start();
	
	$book=$_SESSION['isbn'];
	$card=$_SESSION['card'];
	$branch=$_SESSION["branch_id"];
	$date_in=($_POST["date"]);

	check_in_book($book,$card,$branch,$date_in,$conn);
	
	echo "The book has been checked in! ";
	}

?>




</body>
</html>