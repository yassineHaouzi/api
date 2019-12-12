<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../traitement/traitement.php';

// response array
$response = array();

//error image extension
$errorExt = false;
$checkUploading = false;

if (isset($_POST['user_id'])
    && isset($_POST['first_name'])
    && isset($_POST['last_name'])
    && isset($_POST['email'])
    && isset($_POST['phone'])) {


    // array of image extensions valid
    $imagesExtensionsArray = ['jpg', 'jpeg', 'png'];


    // fields
    $userId = $_POST['user_id'];
    $nom = $_POST['first_name'];
    $prenom = $_POST['last_name'];
    $email = $_POST['email'];
    $telephone = $_POST['phone'];

    //check if image exist
    if (isset($_FILES['image'])) {

        $imageName = addTimeToImages($_FILES['image']['name']);
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageArr = explode(".", $imageName);
        $imageExt = end($imageArr);

        //check if image extension  in array
        if (!in_array($imageExt, $imagesExtensionsArray)) {
            $errorExt = true;
        }

    } else {

        $imageName = "null";
    }

    if (!$errorExt) {

        // call insert user function
        $result = updateUser($userId, $nom, $prenom, $email, $telephone, $imageName);

        // in error case
        if ($result['error']) {
            $response['message'] = $result['message'];
            $response['error'] = true;

        } else {
        //success case

        //upload image
            if ($imageName != "null") {
                $checkUploading = move_uploaded_file($imageTmp, "../assets/images/" . $imageName);

                if ($checkUploading) {

                    $response['message'] = 'user updated successfully';
                    $response['error'] = false;

                } else {

                    $response['message'] = 'image uploading error';
                    $response['error'] = true;
                }
            } else {

                $response['message'] = 'user updated successfully';
                $response['error'] = false;
            }
        }

    } else {

        $response['message'] = 'image extension not valid';
        $response['error'] = true;

    }

} else {

    $response['message'] = 'All fields required';
    $response['error'] = true;

}

echo json_encode($response);

