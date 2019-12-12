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

    $result = getUsers($userID);


    if ($result['error']) {

        $response['error'] = true;
        $response['message'] = $result['message'];


    } else {

        $response['user'] = $result['user'];
        $response['error'] = false;

    }


}else{

    $response['message'] = "User not found !";
    $response['error'] = true;

}




echo json_encode($response);

