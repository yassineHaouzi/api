<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../traitement/traitement.php';

// response array
$response = array();
$response['users'] = array();

    // call users function
    $result = readAllUsers();


    if ($result['error']) {

        $response['error'] = true;
        $response['message'] = $result['message'];


    } else {

       $response['users'] = $result['users'];
        $response['error'] = false;

    }

echo json_encode($response);

