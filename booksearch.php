<html>
   
   <head>
      <title>Library search</title>
   </head>
   
   <body>
      
      <h2>Library search</h2>
      
      <form method = "post" action = "">
         <table>
            <tr>
               <td>ISBN:</td> 
               <td><input type = "text" name = "isbn"></td>
            </tr>
            
            <tr>
               <td>Book title:</td>
               <td><input type = "text" name = "title"></td>
            </tr>
            
            <tr>
               <td>Book Authors:</td>
               <td><input type = "text" name = "authors"></td>
            </tr>

            <tr>
               <td>
                  <input type = "submit" name = "submit" value = "Submit"> 
               </td>
            </tr>
         </table>
      </form>
      <?php
      include 'configurationVars.php';
      include 'connect.php';
      $conn=connectMysql($dbhost,$dbuser,$dbpass,$db,$port);
 
      
      
         
         // define variables and set to empty values
         $isbn = $title = $authors = "";
         
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $isbn = ($_POST["isbn"]);
            $title = ($_POST["title"]);
            $authors = ($_POST["authors"]);
            
           //connect mysql      
            $sql='select * from total_available_books where book_id like "%'.$isbn.'%" and title like "%'.$title.'%" and author_name like "%'.$authors.'%";';             
          
            $retval=mysqli_query($conn, $sql);
      
            if(! $retval ) {
            	die('Could not enter data: ' . mysql_error());
            }         
            
            while($row = mysqli_fetch_assoc($retval)) {
            	echo "book ID :{$row['book_id']}  <br> ".
            			"author NAME : {$row['author_name']} <br> ".
            			"Branch ID : {$row['branch_id']} <br> ".
            			"Branch Name : {$row['branch_name']} <br> ".
            			"no of copies : {$row['no_of_copies']} <br> ".
            			"book title : {$row['title']} <br> ".
            			"book available : {$row['available_books']} <br> ".
            			"--------------------------------<br>";
            }
             
            
            mysqli_close($conn);
         
         }
         
      ?>


      
   </body>
</html>