<?php

// include db and object files
include_once '../config/config.php';
include_once '../config/database.php';
include_once 'helpers.php';

// instantiate db object
 $db = new Database();


// traitement insertion compte

function insertUser($nom,$prenom,$email,$telephone,$mot_de_passe,$image)
{

    global  $db;
    $response = array();

    //check if user already register
    try{

        $db->query('SELECT email from compte WHERE email = :email');
        $db->bind(':email',$email);
        $db->single();
        $count = $db->rowCount();

        if($count != 0 ){
            $response['message'] = 'email déjà existant, veuillez essayer un autre email';
            $response['error'] =true;
        }
        else
        {

            try{

                $nom = secure($nom);
                $prenom = secure($prenom);
                $email = secure($email);
                $mot_de_passe = secure($mot_de_passe);
                $telephone = secure($telephone);

                //hash password
                $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);

                // create user query
                $db->query('INSERT INTO compte(first_name,last_name,email,phone,password,image) VALUES(:nom,:prenom,:email,:telephone,:mot_de_passe,:image)');

                // bind params
                $db->bind(':nom',$nom);
                $db->bind(':prenom',$prenom);
                $db->bind(':email',$email);
                $db->bind(':telephone',$telephone);
                $db->bind(':mot_de_passe',$mot_de_passe);
                $db->bind(':image',$image);

                // execute query
                $result = $db->execute();

                // check result
                if($result) {

                    $response['message'] = 'votre compte créé avec succès, attendre pour activer votre compte';
                    $response['error'] =false;
                }
                else {

                    $response['message'] = 'Oops ! erreur , veuillez réessayer !';
                    $response['error'] =true;

                }

            }
            catch (PDOException $ex){

                $response['message'] =  $ex->getMessage();
                $response['error'] =true;

            }

        }

    }
    catch (PDOException $ex){

        $response['message'] =  $ex->getMessage();
        $response['error'] =true;
    }


    return $response;
}


// traitement read all comptes

function readAllUsers()
{

    global  $db;
    $response = array();
    $response['users'] = array();

    //check if user already register
    try{

        $db->query('SELECT * from compte');

        $users= $db->resultSet();
        $count = $db->rowCount();

        if($count == 0 ){
            $response['message'] = 'Users list empty';
            $response['error'] =false;
        }
        else
        {

            $response['users'] = $users;
            $response['error'] =false;

        }

    }
    catch (PDOException $ex){

        $response['message'] =  $ex->getMessage();
        $response['error'] =true;
    }

    return $response;
}


// traitement read all comptes

function getUsers($userID)
{

    global  $db;
    $response = array();

    // catch query
    try{


        //get user by id
        $db->query('SELECT * from compte WHERE id = :id');

        $db->bind(':id',$userID);

        $user = $db->single();
        $count = $db->rowCount();

        if($count == 0 ){
            $response['message'] = 'User not found';
            $response['error'] =false;
        }
        else
        {

            $response['user'] = $user;
            $response['error'] =false;

        }

    }
    catch (PDOException $ex){

        $response['message'] =  $ex->getMessage();
        $response['error'] =true;
    }

    return $response;
}


// traitement read all comptes

function updateUser($userID,$nom,$prenom,$email,$telephone,$image)
{

    global  $db;
    $response = array();

            try{

                $nom = secure($nom);
                $userID = secure($userID);
                $prenom = secure($prenom);
                $email = secure($email);
                $telephone = secure($telephone);

                $query = 'UPDATE compte  SET first_name = :nom , last_name = :prenom  , email = :email , phone = :telephone ';

                if($image == 'null'){
                    $query .= 'WHERE id = :id';

                }else{

                    $query .= ', image = :image WHERE id = :id' ;
                }



                // create user query
                $db->query($query);

                // bind params
                $db->bind(':nom',$nom);
                $db->bind(':prenom',$prenom);
                $db->bind(':email',$email);
                $db->bind(':telephone',$telephone);
                $db->bind(':id',$userID);
                if($image != 'null' )
                  $db->bind(':image',$image);


                // execute query
                $result = $db->execute();

                // check result
                if($result) {

                    $response['user'] = 'user updated successfully';
                    $response['error'] =false;
                }
                else {

                    $response['message'] = 'Oops ! error , please try again !';
                    $response['error'] =true;

                }

            }
            catch (PDOException $ex){

                $response['message'] =  $ex->getMessage();
                $response['error'] =true;

            }


    return $response;
}


// traitement delete user

function deleteUser($userID){

    global  $db;
    $response = array();

    //check if user already register
    try{

        $db->query('DELETE from compte WHERE id = :id');
        $db->bind('id', $userID);

        $result =  $db->execute();

        if($result){

            $response['message'] = 'User deleted successfully';
            $response['error'] =false;

        }
        else
        {

            $response['message'] = 'Server error ';
            $response['error'] =false;

        }

    }
    catch (PDOException $ex){

        $response['message'] =  $ex->getMessage();
        $response['error'] =true;
    }

    return $response;

}

// login

function login($email,$password){

    global  $db;
    $response = array();

    try{

        $db->query('SELECT * FROM compte WHERE email = :email');

        $db->bind(':email',$email);

        $user = $db->single();
        $count = $db->rowCount();


        if($count != 0){

            $hashed_password = $user->password;
            if(password_verify($password,$hashed_password))
            {
                if($user->confirmation != 0)
                {
                    $response['user'] = $user;
                    $response['error'] = false;

                }else{

                    $response['message'] = "votre compte n’est pas encore activé";
                    $response['error'] = true;
                }
                }
                else{

                    $response['message'] = "email ou mot de passe incorrect !";
                    $response['error'] = true;

                }

        }else{

            $response['message'] = "email ou mot de passe incorrect !";
            $response['error'] =true;
        }

    }
    catch (PDOException $ex){

        $response['message'] =  $ex->getMessage();
        $response['error'] =true;
    }

    return $response;

}



