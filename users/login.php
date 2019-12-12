<?php
// required headers
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");

include_once '../traitement/traitement.php';

// response array
$response = array();


if(isset($_POST['email']) && isset($_POST['password'])){


    $email =secure($_POST['email']);
    $password =secure($_POST['password']);


    // call function login
    $result = login($email,$password);


    if ($result['error']) {

        $response['error'] = true;
        $response['message'] = $result['message'];


    } else {

        $response['user'] = $result['user'];
        $response['error'] = false;

    }

}else{

    $response['message'] = "Tous les champs sont obligatoires .";
    $response['error'] = true;

}




echo json_encode($response);

