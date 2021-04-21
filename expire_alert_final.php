 <?php
 
 include "connection.php";
 mysqli_set_charset($conn, 'utf8');
 $response = null;
 $records  = null;
 extract($_POST);

$expire = date('Y-m-d', strtotime('+1 days')); // renewal alert 3 days
$alrt = date('Y-m-d', strtotime('+2 days')); //renewal alert 1 day
$alrt2 = date('Y-m-d', strtotime('+3 days')); //renewal alert 2 days

    
    // Prepare a select statement
    $query = "SELECT c.firstName,c.lastName,c.email,t.userId,t.transactionId,t.purchaseType,t.serviceEndDate
 FROM transaction_master t JOIN customer_master c ON c.customerId = t.userId 
 WHERE DATEDIFF(CURRENT_DATE, serviceEndDate) = -3 OR DATEDIFF(CURRENT_DATE, serviceEndDate) = -2 OR DATEDIFF(CURRENT_DATE, serviceEndDate) = -1";



// Perform Query
$result = mysqli_query($conn,$query);

// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
$errMsg  = 'Invalid query: ' . mysql_error() . "\n";
$errMsg .= 'Whole query: ' . $query;
die($errMsg);
}

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
    $email = $row['email'];
    $userId = $row['userId'];
    $purchaseType = $row['purchaseType'];
    $serviceEndDate = $row['serviceEndDate'];


    
    if (strtotime($serviceEndDate) < strtotime($alrt))
    {
        // echo "<h2>expiring tommorow.Please pay</h2>".$firstName;
        $message = "<h2>Hi ". $firstName. " ".$lastName. " your ". $purchaseType. " package expiring tommorow (".$serviceEndDate.").\nPlease purchase or renew pack.</h2><br>";
        echo $message;
    }
    else if (strtotime($serviceEndDate) < strtotime($alrt2))
    {
        // echo "<h2>expiring in 2 days.Please pay</h2>".$firstName;
        $message = "<h2>Hi ". $firstName. " ".$lastName. " your ". $purchaseType. " package expiring in 2 days (".$serviceEndDate.").\nPlease purchase or renew pack.</h2><br>";
echo $message;

    }
   else if (strtotime($serviceEndDate) > strtotime($expire))
{
    // echo "<h2>expiring in 3 days.Please purchase or renew pack.</h2>".$firstName;
    $message = "<h2>Hi ". $firstName. " ".$lastName. " your ". $purchaseType. " package expiring in 3 days (".$serviceEndDate.").\nPlease purchase or renew pack.</h2><br>";
echo $message;
    //
    
}


    // echo "Hi ". $firstName. " ".$lastName. " your ". $purchaseType. " expiring on:".$serviceEndDate. "<br>";

         $to = $email;
         $subject = "ECCA Package Expiration Alert!";
         
         // $message = "Hi ". $firstName. " ".$lastName. " your ". $purchaseType. " expiring on:".$serviceEndDate;
      
         
         $header = "From:wasim.shaikh@nucleonai.co.in \r\n";
         $header .= "Cc:wasim.shaikh@nucleonai.co.in \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);
         
         if( $retval == true ) {
            echo "Message sent successfully...";
         }else {
            echo "Message could not be sent..."."<br>";
         }

}



?>
