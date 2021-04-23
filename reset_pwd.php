<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once "db_functions.php";

//check if the username can be found
if(empty($_POST['username'])){
	echo json_encode(["success" => false, "message" => "The password request can't be processed no username provided"]);
	return;
}
if(empty($_POST['password'])){
	echo json_encode(["success" => false, "message" => "The password request can't be processed no new password provided"]);
	return;
}

//check the information
$user = first($db, "SELECT * FROM users WHERE username = ?", [$_POST['username']]);
if($user) {
	saveData($db, "UPDATE users SET password  = ? WHERE id = ?", [hash("sha256", $_POST['password']), $user['id']]);
	echo json_encode(["success" => true, "message" => "Password Successfuly resetted", "url" => ""]);
	return;
} else {
	echo json_encode(["success" => false, "message" => "Password not resetted provided username cano not be found on the system"]);
	return;
}