<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once "db_functions.php";

//validate the information here
if(empty($_POST['name'])){
	echo json_encode(["success" => false, "message" => "The Name is requried"]);
	return;
}
if(empty($_POST['username'])){
	echo json_encode(["success" => false, "message" => "The Username is requried"]);
	return;
}
if(empty($_POST['password'])){
	echo json_encode(["success" => false, "message" => "The Password is requried"]);
	return;
}

if($_POST['password'] !== $_POST['confirm_password']){
	echo json_encode(["success" => false, "message" => "Password mismatch error found!"]);
	return;
}

//check if the username is used
$user = first($db, "SELECT * FROM users WHERE username = ?", [$_POST['username']]);
// var_dump($user);
if($user){
	echo json_encode(["success" => false, "message" => sprintf("%s as username is already taken", $_POST['username'])]);
	return;
}
$db->beginTransaction();
try{
	//save information
	saveData($db, "INSERT INTO users SET name = ?, username = ?, password = ?", [$_POST['name'], $_POST['username'], hash("sha256", $_POST['confirm_password'])]);

	$registered = first($db, "SELECT id, name, username FROM users WHERE username = ? AND password = ?", [$_POST['username'], hash("sha256", $_POST['password'])]);

	if( count($registered) > 0 ){
		$_SESSION['user'] = $registered;
		$db->commit();
		echo json_encode(["success" => true, "message" => "New User registration accepted!", "url" => "welcome.php"]);
		return;
	}
} catch(\Exception $e){
	$db->rollback();
	echo json_encode(["success" => false, "message" => $e->getMessage()]);
	return;
}