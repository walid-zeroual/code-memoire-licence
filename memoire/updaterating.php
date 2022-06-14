<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'db.php';


$updaterating = file_get_contents('php://input');
$updaterating = json_decode($updaterating,true);


function updateRating($updaterating){
	$updateResult = false;
	
	global $con;
	$username = $updaterating['username'];
	$itemName = $updaterating['coursename'];
	$rating   = $updaterating['courserating'];
	$updateQuery ="UPDATE ratings SET rating ='".$rating."'
				where userName='".$username."'
				AND itemName='".$itemName."'  ; ";//username could be $_SESSION["username"]
	$updateResult = mysqli_query($con, $updateQuery);
	
	return $updaterating;
}

function setUpdateRating($updaterating){
	if(updaterating($updaterating))
    {  
        $data =$updaterating;
        $data = json_encode($data,true);  
        print_r($data);
	}
	else
    {
        
        $data =$updaterating;
        $data = json_encode($data,true);
        print_r($data);
    }
}

//uncomment to update rating with user submitted values
setUpdateRating($updaterating);