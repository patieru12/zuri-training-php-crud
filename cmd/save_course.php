<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
if(!isset($_SESSION['user'])){
  // header("location:../logout.php");
	echo json_encode(["success" => true, "message" => "Unauthenticated request found!", "url" => "logout.php"]);
  	exit();
}
require_once "../db_functions.php";

//now check if the course is submitted
if(empty($_POST['name'])){
	echo json_encode(["success" => false, "message" => "Please specify the course name"]);
  	exit();
}

//check if the couse is registered before
$course = first($db, "SELECT * FROM courses WHERE name = ? AND user_id = ?", [$_POST['name'], $_SESSION['user']['id']]);
if($course){
	echo json_encode(["success" => false, "message" => "course registered before!"]);
  	exit();
}


//now save the course information
try{
	saveData($db, "INSERT INTO courses SET user_id = ?, name=?, created_at = ?, updated_at = ?", [
		$_SESSION['user']['id'],
		$_POST['name'],
		(new DateTime())->format("Y-m-d H:i:s"),
		(new DateTime())->format("Y-m-d H:i:s")
	]);
	echo json_encode(["success" => true, "message" => "New course registered", "url" => "welcome.php"]);
	return;
} catch(\Exception $e){
	echo json_encode(["success" => false, "message" => $e->getMessage(),]);
	return;
}