<?php

function check_borrower_quota($card_no,$conn){
	
$sql='select count(bl.loan_id) as borrowed from book_loans bl where card_no="'.$card_no.'";';
$retval=mysqli_query($conn, $sql);
if(! $retval ) {
	die('Could not fetch data from book_loans: ' . mysql_error());
}

$row = mysqli_fetch_assoc($retval); 
if($row["borrowed"]>=3){
		return false;
	}
else
	return true;
}


function reserve_book($isbn,$branch,$card_no,$conn){

	$loan_id=uniqid("",true);
	$sql="insert into book_loans (loan_id,isbn,branch_id,card_no,date_out,due_date) values ('".$loan_id."','".$isbn."','".$branch."','".$card_no."',curdate(),date_add(curdate(),interval 14 day));";	
	
	 $retval=mysqli_query($conn, $sql);
      if(! $retval ) {
      	die('Could not enter book loans data: ' . mysql_error());
      }
   }
   
function check_available_book($isbn,$branch,$conn){
	
}

function check_book_availability($isbn,$branch,$conn){
	//check whether the number of book_loans for a given book at branch already equals the no_of_copies, return fail
	$sql='select bc.no_of_copies from book_copies bc where branch_id="'.$branch.'" and book_id="'.$isbn.'";';
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('Could not fetch book availability data on book_copies:' . mysql_error());
	}
	
	
	$row=mysqli_fetch_assoc($retval);
	$no_branch_copies=$row["no_of_copies"];
	return $no_branch_copies;
	
}

function no_borrowed_branch($isbn,$branch,$conn){
	$sql='select count(isbn) as no from book_loans where branch_id="'.$branch.'" and isbn="'.$isbn.'";';
	$retval=mysqli_query($conn, $sql);
	
	if(! $retval ) {
		die('Could not fetch book availability data on book_loans: ' . mysql_error());
	}
	
	
	$row=mysqli_fetch_assoc($retval);
	$no_borrowed_branch=$row["no"];
	return $no_borrowed_branch;
}

function check_in_list($book,$card,$borrower,$fname,$lname,$conn){
	
	
	$sql='select * from book_loans bl, borrower br where br.card_no=bl.card_no and bl.isbn like "%'.$book.'%" and bl.card_no like "%'.$card.'%" and br.lname like "%'.$lname.'%" and br.fname like "%'.$fname.'%";';

	$retval=mysqli_query($conn, $sql);
	
	if(! $retval ) {
		die('Could not fetch from db ' . mysql_error());
	}
	
	
	
	return $retval;
	
}

function check_in_book($book,$card,$branch,$date_in,$conn){
	
	$sql='update book_loans set date_in="'.$date_in.'" where isbn="'.$book.'" and card_no="'.$card.'" and branch_id="'.$branch.'";';
	
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('check in book failed ' . mysql_error());
	}
	
	
}

function generateCardNo($conn){
	
	$sql='select count(card_no) from borrower;';
	$retval=mysqli_query($conn, $sql);
	$row=mysqli_fetch_row($retval);
	$result=$row[0];
	
	$newcard='ID'.str_pad($result+1,6,"0",STR_PAD_LEFT);
	return $newcard;
}

function addBorrower($fname,$lname,$card,$phone,$address,$ssn,$conn){
	
	$sql='insert into borrower (card_no,ssn,fname,lname,address,phone) values ("'.$card.'","'.$ssn.'","'.$fname.'","'.$lname.'","'.$address.'","'.$phone.'");';
	print $sql;
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('add borrower failed ' . mysql_error());
	}
	
	
}

function check_conflicts($conn,$name,$table,$value){
	
	$sql='select '.$name.' from '.$table.' where '.$name.'="'.$value.'";';
	
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('check conflicts book failed ' . mysql_error());
	}
	
	$result = mysqli_num_rows($retval);
	
	if($result==0)
		return false;
	else 
		return true;
		
}

function calReturnedBookFee($unitprice,$checkoutDay,$checkinDay){
	$checkoutDay=date_create($checkoutDay);
	$checkinDay=date_create($checkinDay);
	$interval=date_diff($checkinDay,$checkoutDay);
	$days=$interval->format('%a');
	return $days*$unitprice;
	
}

function fineScan($conn,$unitprice){
	$sql='select * from book_loans';
	$retval=mysqli_query($conn, $sql);
	
	if(! $retval ) {
		die('Could not fetch book loans data ' . mysql_error());
	}
	
	

	while($row=mysqli_fetch_assoc($retval)) {

		
		$due_day=$row['due_date'];
		$today=date("Y-m-d");
		$checkin_date=$row['date_in'];
		$loan_id=$row['loan_id'];
		
		if(($checkin_date == '0000-00-00') and ($due_day<$today)){		
			//book not returned and due day pass 
			
			$fine_amt=calReturnedBookFee($unitprice,$due_day,$today);
			
			updateFinesTable($loan_id,$fine_amt,$conn);
		}
		else if($due_day<$checkin_date){
			//book returned and due day pass
			
			$fine_amt=calReturnedBookFee($unitprice,$due_day,$checkin_date);
			
			updateFinesTable($loan_id,$fine_amt,$conn);
		}
	}
}

function updateFinesTable($loan,$fine_amt,$conn){
	$sql='select loan_id from fines where loan_id="'.$loan.'";';
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('Could not fetch fines data ' . mysql_error());
	}
	
	
	$row= mysqli_fetch_row($retval);
	
	if($row[0]==''){
		//insert into fines
		$sql='insert into fines (loan_id,fine_amt,paid) values ("'.$loan.'" , " '.$fine_amt.'" , "false");';
		$retval=mysqli_query($conn, $sql);
		if(! $retval ) {
			die('Could not add fines data ' . mysql_error());
		}
		
		
	}
	else if($row[0]==$loan and $row[2]==false and $row[1]<$fine_amt){
		$sql='update fines set fine_amt='.$fine_amt.' where loan_id="'.$loan.'";'; 
		$retval=mysqli_query($conn, $sql);
		if(! $retval ) {
			die('Could not update fines data ' . mysql_error());
		}
		
	}

	
}

function payFine($loan,$conn){
	$sql='select * from fines where loan_id="'.$loan.'";';
	$retval=mysqli_query($conn, $sql);
	if(! $retval ) {
		die('Could not fetch fines data ' . mysql_error());
	}
	
	
	$row= mysqli_fetch_row($retval);
	if($row[2]==false){
		$sql='update fines set paid="'.true.'" where loan_id="'.$loan.'";';
		$retval=mysqli_query($conn, $sql);
		if(! $retval ) {
			die('Could not update fines data paid ' . mysql_error());
		}
		
	}
	else
		die('fine paid ');
	
}

function displayFine($conn){
	$sql='select bl.card_no as card,bl.loan_id as loan,sum(f.fine_amt) as fine from book_loans bl, fines f where f.paid=false and bl.loan_id=f.loan_id group by bl.card_no;';
	$retval=mysqli_query($conn, $sql);
	
	if(! $retval ) {
		die('Could not enter data: ' . mysql_error());
	}
	
	while($row = mysqli_fetch_assoc($retval)) {
		echo "Card No :{$row['card']}  <br> ".
				"Loan ID : {$row['loan']} <br> ".
				"Fine Amount : {$row['fine']} <br> ".
				"--------------------------------<br>";
	}
	 
	
}

   
?>