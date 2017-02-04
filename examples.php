<?php
//Usage Example of Traccar PHP API.
// - James

include('traccar.php');

//User Submission data (Please change according to your requirement)

$username = 'admin';
$password = 'admin';

//Login Start
$t=traccar::login($username,$password);
if($t->responseCode=='200') {
   $traccarCookie = traccar::$cookie;
   echo 'Login Successfull';
}else{
  echo 'Incorrect username or password';
}
//Login End

//User Regster Start

//User Register End

?>
