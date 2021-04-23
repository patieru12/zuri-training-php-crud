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
if(empty($_POST['id'])){
	echo json_encode(["success" => false, "message" => "Some info are missing"]);
  	exit();
}

//check if the couse is registered before
$course = first($db, "SELECT * FROM courses WHERE name = ? AND user_id = ? AND id != ?", [$_POST['name'], $_SESSION['user']['id'], $_POST['id']]);
if($course){
	echo json_encode(["success" => false, "message" => "Course registered before!"]);
  	exit();
}


//now save the course information
try{
	saveData($db, "UPDATE courses SET user_id = ?, name=?, updated_at = ? WHERE id = ?", [
		$_SESSION['user']['id'],
		$_POST['name'],
		(new DateTime())->format("Y-m-d H:i:s"),
		$_POST['id']
	]);
	echo json_encode(["success" => true, "message" => "Course updated", "url" => "welcome.php"]);
	return;
} catch(\Exception $e){
	echo json_encode(["success" => false, "message" => $e->getMessage(),]);
	return;
}