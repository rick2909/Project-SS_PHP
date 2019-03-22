<?php
session_start();
//lees het config
require_once 'include/config.inc.php';

//lees post
$a = $_POST['email'];
$b = $_POST['password'];

$email = htmlspecialchars(mysqli_real_escape_string($mysqli, $a));
$password = htmlspecialchars(mysqli_real_escape_string($mysqli, $b));

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
		$rows = []
		while($r = mysqli_fetch_assoc($result)) {
			$rows[""] = $r;
		}

		$_SESSION['user'] = "admin";
		$response['error'] = FALSE;
		$response['message'] = "u bent ingelogd";
		$response['user'] = json_encode($rows);
	} else {
		$response['message'] = 'verkeerde wachtwoord en of gebruikersnaam';
	}
} else{
	$response['message'] = 'Er is niks ingevoerd';
}
echo json_encode($response);
?>