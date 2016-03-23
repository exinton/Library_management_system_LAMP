<html>

<head>

<title>Library book checkin!</title>

</head>
<body>
<h2>
check fine!
</h2>

 <form action="" method="post">
  <input type="submit" name="display" value="display">
  <input type="submit" name="refresh" value="refresh">
</form> 



</body>


<?php
include 'check_data.php';
include 'configurationVars.php';
include 'connect.php';
$conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);

if(!empty($_POST['refresh'])){
	fineScan($conn,$unitprice);
	
}

if(!empty($_POST['display'])){

	displayFine($conn);

}




?>



</html>