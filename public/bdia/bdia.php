<?php
require_once './vendor/autoload.php';

use Twilio\Rest\Client;


$sid = "AC50dc288abbf2009ffa6b4b64aa20993d";
$token = "5f59077b07db002168bfa9796fe72dba";


$twilio = new Client($sid, $token);

$d = $_POST['distance'];
$msg = "Niveau eau:" . $d . " cm"; 


header('Status:200');
print("SMS sent. ");


// $message = $twilio->messages
//                   ->create("+212666561940", // to
//                            ["body" => $msg, "from" => "+15139606835"]
//                   );

// header('Status:200');
// print($message->sid);

