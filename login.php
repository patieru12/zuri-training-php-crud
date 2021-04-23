<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once "db_functions.php";

//Check if the username is ready
if(empty($_POST['username'])){
	echo json_encode(["success" => false, "message" => "Please provide username"]);
	return;
}
if(empty($_POST['password'])){
	echo json_encode(["success" => false, "message" => "Please provide password"]);
	return;
}

//try to login the user
$user = first($db, "SELECT id, name, username FROM users WHERE username = ? AND password = ?", [$_POST['username'], hash("sha256", $_POST['password'])]);

if(is_array($user) && count($user) > 0){
	$_SESSION['user'] = $user;
	echo json_encode(["success" => true, "message" => "Authentication success", "url" => "welcome.php"]);
	return;
} else {
	echo json_encode(["success" => false, "message" => "Invalid credentials found!"]);
}

