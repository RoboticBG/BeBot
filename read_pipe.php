<?php
include_once "config.php";

// From Ajax
$cmd = $_POST['cmd'];

$response_arr = array();

$response_arr['message'] = file_get_contents("$ram_fsdir/prog-output.txt");
if($cmd) $response_arr['message'] = "CMD: $cmd | ".$response_arr['message']. " | ";

echo json_encode($response_arr);


?>
