<?php

//lees het config
//require_once 'include/config.inc.php';

require_once 'include/config2.inc.php';

$query = array();

//create functies
// $query[] = "INSERT INTO `functie` (`id`, `naam`, `extra_info`) VALUES (NULL, 'admin', 'this is the admin')";
// $query[] = "INSERT INTO `functie` (`id`, `naam`, `extra_info`) VALUES (NULL, 'client', 'this is the client')";

// //create locaties
// $query[] = "INSERT INTO `locatie` (`id`, `stad`, `adres`, `postcode`, `longitude`, `latitude`)
//             VALUES (NULL, 'Berkel en Rodenrijs', 'Rodenrijseweg 55', '2651BN', '4.472828', '51.990679')";
// $query[] = "INSERT INTO `locatie` (`id`, `stad`, `adres`, `postcode`, `longitude`, `latitude`)
//             VALUES (NULL, 'Berschenhoek', 'Tobias Asserlaan 1', '2662SB', '4.493406', '51.981631')";
// $query[] = "INSERT INTO `locatie` (`id`, `stad`, `adres`, `postcode`, `longitude`, `latitude`)
//             VALUES (NULL, 'Berkel en Rodenrijs', 'Archimedesstraat 1', '2652XR', '4.452851', '51.982310')";
// $query[] = "INSERT INTO `locatie` (`id`, `stad`, `adres`, `postcode`, `longitude`, `latitude`)
//             VALUES (NULL, 'Rotterdam', 'Wijnhaven 107', '3011WN', '4.483993', '51.917227')";
// $query[] = "INSERT INTO `locatie` (`id`, `stad`, `adres`, `postcode`, `longitude`, `latitude`)
//             VALUES (NULL, 'Rotterdam', 'Blijdorplaan 8', '3041JG', '4.443933', '51.928310')";
// $query[] = "INSERT INTO `locatie` (`id`, `stad`, `adres`, `postcode`, `longitude`, `latitude`)
//             VALUES (NULL, 'Rotterdam', 'Rotterdam Airportplein 60', '3045AP', '4.441171', '51.955835')";

//create admin
$query[] = "INSERT INTO `gebruikers` (`id`, `email`, `wachtwoord`, `achternaam`, `initialen`, `geslacht`, `locatie_id`, `functie_id`) 
            VALUES (NULL, 'test@test.nl', 'df116d669dfb298c2b996711b876b6b0ab84a66a', 'admin test', 'T.a.T', '1', '1', '1')";

//create client
for($i = 1; $i <= 6; $i++){
    $rand = rand();
    $g = rand(0 , 1);
    $email = $rand . "@test.nl";
    $query[] = "INSERT INTO `gebruikers` (`id`, `email`, `wachtwoord`, `achternaam`, `initialen`, `geslacht`, `locatie_id`, `functie_id`) 
                VALUES (NULL, '$email', 'df116d669dfb298c2b996711b876b6b0ab84a66a', '$rand', 'T.c.T', '$g', '$i', '2')";
}

//create rooster voor admin
$query[] = "INSERT INTO `rooster` (`id`, `gebruikers_id`) VALUES (NULL, '1')";

//creat gebruikers voor in het rooster
for($dag = 1; $dag <= 5; $dag++){
    for($i = 2; $i <= 7; $i++){
        $tijd = date('H:i:s' , strtotime('10:00:00') + 60*60*$i);

        $query[] = "INSERT INTO `client_in_rooster` (`id`, `rooster_id`, `gebruikers_id`, `dag_van_de_week`, `tijd`) 
                    VALUES (NULL, '1', '$i', '$dag', '$tijd')";
    }
}

//create raport
for($i = 2; $i <= 7; $i++){
    $rand = rand(99999, 99999999);
    $protocol = "TEST PROTOCOL! " . $rand;

    $query[] = "INSERT INTO `raporteren` (`id`, `protocol`, `gebruikers_id`, `updated_at`) 
                VALUES (NULL, '$protocol', '$i', NULL)";
}

//create opmerking
for($i = 1; $i <= 6; $i++){
    $titel = rand(99999, 99999999);

    $opmerking = "TEST OPMERKING!";

    $query[] = "INSERT INTO `opmerkingen` (`id`, `titel`, `opmerking`, `raport_id`, `created_at`) 
                VALUES (NULL, '$titel', '$opmerking', '$i', NULL)";
}

foreach($query as $q){
    $response = array();
    if(mysqli_query($mysqli, $q)){
        $response['message'] = 'SEEDER WORKED!';
        $response['error'] = FALSE;
    }else{
        $response['message'] = 'er was een probleem opgetreden probeer later opnieuw of meldt het aan een admin';
        $response['error'] = mysqli_error( $mysqli );
        break;
    }
}
echo json_encode($response);
?>