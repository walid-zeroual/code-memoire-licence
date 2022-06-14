<?php
//ini_set("allow_url_fopen", 1);
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'getDataFromDB.php';

$register = file_get_contents('php://input');
$register = json_decode($register,true);


function isValidRegistration($registerJson){
	return $registerJson['register']=='true';
}

function registerUser($registerJson){
	
	$result = false;
	if(isValidRegistration($registerJson)){
		global $con;
        //removes backslashes
        //escapes special characters in a string according to db settings
        $username = mysqli_real_escape_string($con,stripslashes($registerJson['username']));
        $password = mysqli_real_escape_string($con,stripslashes($registerJson['password']));
        $email    = mysqli_real_escape_string($con,stripslashes($registerJson['email']));
        $create_datetime = date("Y-m-d H:i:s");
        $query    = "INSERT into `users` (userName, password, email, create_datetime)
                     VALUES ('$username', '" . md5($password) . "', '$email', '$create_datetime')";
        try
        {
        	$result   = mysqli_query($con, $query);
        }
        catch(Exception $e)
        {
        	$result = false;
        }
	}
	
	return $result;

}
//chaque nouvel utilisateur est ajouté a la matrice des notes avec 0 pour tous les articles
function addNewUserToRatingsTableWithZeroRatingForAllItems($register){
	global $con;
	$items = getAllItems();
	$result =true;
	$sql = "INSERT INTO ratings(userName,itemName,rating) VALUES";
	for($i=0;$i<count($items);$i++)
	{
		$query = "('".$register['username']."','".$items[$i]."',0),";
		$sql.=$query;
	}
	$sql=rtrim($sql,",");
	$sql.=";";

	try
	{
		$result   = mysqli_query($con, $sql);
	}
	catch (Exception $e)
	{
		$result = false;
	}
	return $result;
}

function setRegisterJson($registerJson)
{

if(registerUser($registerJson)){
	
	
	$adduser=addNewUserToRatingsTableWithZeroRatingForAllItems($registerJson);
	if($adduser)
	{
		http_response_code(200);
		$response = json_encode($registerJson,true);
		print_r($response);
	}
}
else {
	http_response_code(404);
	$registerJson['register']= 'false';
	$response = json_encode($registerJson,true);
	print_r($response);
}
}
//uncomment this to send register information  as Json
setRegisterJson($register);