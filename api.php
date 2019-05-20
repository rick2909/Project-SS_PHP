<?php

//lees het config
//require_once 'include/config.inc.php';

require_once 'include/config2.inc.php';

$arguments = [];

foreach ($_POST as $name => $val) {
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

    //geef de juiste dag mee
    $arguments['dag'] = date('Y-m-d');;

    // haal de mensen op die in het rooster staan
    $rooster = getRooster($arguments, $mysqli);

    $rooster1 = json_decode($rooster, TRUE);
    $clienten = $rooster1['rooster'];

    if(count($clienten) > 1{
        $query = "SELECT * FROM `rooster` WHERE `gebruikers-id` = '$id'";

        $result = mysqli_query($mysqli, $query);

        

            return;
        } else {
            $response['message'] = 'Gebruiker heeft geen rooster';
        }
    }else{
        $response['message'] = 'Er zijn geen clienten vandaag. U bent vrij!';
    }
    echo json_encode($response);
    return json_encode($response);
}

function getRooster($arguments, $mysqli){
    $id = $arguments['id'];
    $dag = $arguments['dag'];

    //make response
    $response = array();
    $response['error'] = TRUE;

    // haal de mensen op die in het rooster staan
    if(strlen($id) > 0){
        $query = "SELECT * FROM `rooster` WHERE `gebruikers-id` = '$id'";

        $result = mysqli_query($mysqli, $query);

        //check of dat de gebruiker een rooster heeft
        if (mysqli_num_rows($result) == 1){
            $rooster = mysqli_fetch_assoc($result);

            $roosterId = $rooster['id'];

            if( isset($roosterId) ){
                $query = "SELECT * FROM `rooster_gebruikers` WHERE `rooster-id` = '$roosterId' AND `dag` = '$dag' ORDER BY `tijd`";

                $result = mysqli_query($mysqli, $query);

                if(mysqli_num_rows($result) >= 1){
                    $data = array();
                    while ($row = mysqli_fetch_assoc( $result )){
                        $data[] = $row;
                    }
                    //create response
                    $response['rooster'] = $data;
                    $response['error'] = FALSE;
                    $response['message'] = 'Rooster ophalen was succesvol';
                } else{
                    $response['message'] = 'U heeft geen clienten';
                }
            } else {
                $response['message'] = 'ID was onjuist';
            }
        } else {
            $response['message'] = 'Gebruiker heeft geen rooster';
        }
    }else{
        $response['message'] = 'Er is geen id opgegeven';
    }
    echo json_encode($response);
    return json_encode($response);;
}

?>