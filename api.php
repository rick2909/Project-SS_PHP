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
    return json_encode($response);
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
    $rooster = json_decode($rooster, TRUE);

    if( !$rooster['error'] ){
        $clienten = $rooster['rooster'];

        $locations = array();
        $clientenGegevens = array();
        foreach($clienten as $roosterClient){
            $clientId = $roosterClient['id'];

            $query = "SELECT `id`, `achternaam`, `initialen`, `Geslacht`, `locatie-id` FROM `gebruikers` WHERE `id` = '$clientId'";
            $result = mysqli_query($mysqli, $query);

            if(mysqli_num_rows($result) == 1){
                $client = mysqli_fetch_assoc( $result );

                $clientenGegevens[] = $client;

                $locatieId = $client['locatie-id'];

                $query = "SELECT * FROM `locatie` WHERE `id` = '$locatieId'";
                $result = mysqli_query($mysqli, $query);

                if(mysqli_num_rows($result) == 1){
                    $locations[$client['id']] = mysqli_fetch_assoc( $result );
                }
            }

            $response['error'] = FALSE;
            $response['locaties'] = $locations;
            $response['clienten'] = $clientenGegevens;
            $response['message'] = 'Hier de locaties.';
        }
    }else{
        $response['message'] = 'Er zijn geen clienten vandaag. U bent vrij!';
    }
    echo json_encode($response);
    return json_encode($response);
}

function getRooster($arguments, $mysqli){
    $id = $arguments['id'];

    //make response
    $response = array();
    $response['error'] = TRUE;

    $days = [
        1 => 'Maandag',
        2 => 'Dinsdag',
        3 => 'Woensdag',
        4 => 'Donderdag',
        5 => 'Vrijdag',
        6 => 'Zaterdag',
        7 => 'Zondag'
    ];

    // haal de mensen op die in het rooster staan
    if(strlen($id) > 0){
        $query = "SELECT * FROM `rooster` WHERE `gebruikers-id` = '$id'";

        $result = mysqli_query($mysqli, $query);

        //check of dat de gebruiker een rooster heeft
        if (mysqli_num_rows($result) == 1){
            $rooster = mysqli_fetch_assoc($result);

            $roosterId = $rooster['id'];

            if( isset($roosterId) ){
                $query = "SELECT * FROM `rooster_gebruikers` WHERE `rooster-id` = '$roosterId' ORDER BY `tijd`";

                $result = mysqli_query($mysqli, $query);

                if(mysqli_num_rows($result) >= 1){
                    $data = array();
                    while ($row = mysqli_fetch_assoc( $result )){
                        $row['dagVanDeWeek'] = $days[$row['dagVanDeWeek']];
                        $data[] = $row;
                    }

                    //create response
                    $response['rooster'] = $data;
                    $response['error'] = FALSE;
                    $response['message'] = 'Rooster ophalen was succesvol';
                } else{
                    $response['message'] = 'U heeft geen clienten op deze dag';
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
    return json_encode($response);
}

function upadateProtocol($arguments, $mysqli){
    $id = $arguments['id'];
    $protocol = $arguments['protocol'];

    //make response
    $response = array();

    if(strlen($id) > 0){
        $query = "UPDATE `raporteren` SET `Protocol` = '$protocol' WHERE gebruikers_id = '$id'";

        if(mysqli_query($mysqli, $query)){
            $response['message'] = 'Update is succesvol met: ' . $protocol;
            $response['error'] = FALSE;
        }else{
            $response['message'] = 'er was een probleem opgetrede probeer later opnieuw of meldt het aan een admin';
            $response['error'] = TRUE;
        }
    }else{
        $response['message'] = 'Geen id opgegeven';
        $response['error'] = TRUE;
    }

    echo json_encode($response);
    return json_encode($response);
}

function getProtocol($arguments, $mysqli){
    $id = $arguments['id'];

    //make response
    $response = array();
    $response['error'] = TRUE;

    if(strlen($id) > 0){
        $query = "SELECT * FROM `raporteren` WHERE gebruikers_id = '$id'";
        $result = mysqli_query($mysqli, $query);

        if(mysqli_num_rows($result) == 1){
            $response['Protocol'] = mysqli_fetch_assoc( $result );
            $response['message'] = 'Protocol is succesvol opgehaalt';
            $response['error'] = FALSE;
        }else {
            $response['message'] = 'Er is geen protocol voor deze persoon melt het of maak er een aan';
        }

    }else{
        $response['message'] = 'Geen id opgegeven';
    }
    echo json_encode($response);
    return json_encode($response);
}

?>