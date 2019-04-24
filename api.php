<?php

//lees het config
//require_once 'include/config.inc.php';

require_once 'include/config2.inc.php';

$arguments = [];

foreach ($_POST as $name => $val)
{
    // htmlspecialchars(mysqli_real_escape_string()) it to prevent unwanted code being runed
    $arguments[$name] = htmlspecialchars(mysqli_real_escape_string($mysqli, $val));
}

main($arguments, $mysqli);

function main ($arguments, $mysqli) {
    if(isset($arguments["function"])){
        $function = $arguments["function"];
        unset($arguments["function"]);
        call_user_func($function, $arguments, $mysqli);
    }
}

function login($arguments, $mysqli){
    $email = $arguments["email"];
    $password = $arguments["password"];

    //make response
    $response = array();
    $response['error'] = TRUE;

    //check if filled
    if (strlen($email) > 0 && strlen($password) > 0) {
        //encrypt
        $password = sha1($password);

        //query for control
        $query = "SELECT * FROM `gebruikers` WHERE `email` = '$email' AND `wachtwoord` = '$password'";

        //query work
        $result = mysqli_query($mysqli, $query);

        //check if login correct
        if (mysqli_num_rows($result) == 1){
            $user = mysqli_fetch_assoc($result);

            $_SESSION['user'] = "admin";
            $response['error'] = FALSE;
            $response['message'] = "u bent ingelogd";
            $response['user'] = json_encode($user);
        } else {
            $response['message'] = 'verkeerde wachtwoord en of gebruikersnaam';
        }
    } else{
        $response['message'] = 'Er is niks ingevoerd';
    }
    echo json_encode($response);
    return;
}

function getMaps($arguments, $mysqli){
    $id = $arguments['id'];

    //make response
    $response = array();
    $response['error'] = TRUE;

    // haal de mensen op die in het rooster staan
    if(strlen($email) > 0){
        $query = "SELECT 'id' FROM `rooster` WHERE `gebruikers_id` = '$id'";

        $result = mysqli_query($mysqli, $query);

        //check if login correct
        if (mysqli_num_rows($result) == 1){
            $rooster = mysqli_fetch_assoc($result);

            $roosterId = $rooster->id;
        } else {
            $response['message'] = 'verkeerde wachtwoord en of gebruikersnaam';
        }
    }else{
        $response['message'] = 'Er is geen id gegeven';
    }






    $query = "SELECT * FROM `rooster_gebruikers` WHERE `rooster-id` = '$roosterId' AND `wachtwoord` = '$password'";

    //query for control
    $query = "SELECT * FROM `locatie` WHERE `email` = '$email' AND `wachtwoord` = '$password'";

    echo json_encode($response);
    return;
}

?>