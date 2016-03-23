<html>

<head>

<title>borrower management</title>

</head>
<body>
<h2>
borrower management
</h2>

 <form action="" method="post">

  first name: <input type="text" name="fname"><br>
  last name: <input type="text" name="lname"><br>
  SSN: <input type="text" name="ssn"><br>
  Address: <input type="text" name="address"><br>
  Phone: <input type="text" name="phone"><br>

  <input type="submit" name="addborrower-submit" value="add">
</form> 

<?php
include 'check_data.php';
include 'configurationVars.php';
include 'connect.php';
$conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);

if(!empty($_POST['addborrower-submit'])){

$card=generateCardNo($conn);
$fname=$_POST["fname"];
$lname=$_POST["lname"];
$phone=$_POST["phone"];
$address=$_POST["address"];
$ssn=$_POST["ssn"];

if($fname=="" or $lname =="" or $phone =="" or $address=="" or ssn=="" ){
	die ('empty input');
}


$name="ssn";
$table="borrower";
$result=check_conflicts($conn,$name,$table,$ssn);

if(! $result){
	addBorrower($fname,$lname,$card,$phone,$address,$ssn,$conn);
}
else
	print "ssn conflicts";



}
?>




</body>
</html>
