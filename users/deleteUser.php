<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../traitement/traitement.php';

// response array
$response = array();


if(isset($_POST['user_id'])){

    // call function find user by id
    $userID =secure($_POST['user_id']);

    $result = deleteUser($userID);


    if ($result['error']) {

        $response['error'] = true;
    } else {

        $response['error'] = false;
    }

    $response['message'] = $result["message"];

}else{

    $response['message'] = "User not found !";
    $response['error'] = true;

}




echo json_encode($response);

