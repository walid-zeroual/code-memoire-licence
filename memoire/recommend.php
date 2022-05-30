<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
//error_reporting(0);
include 'matrix.php';
$recommend = file_get_contents('php://input');
$recommend = json_decode($recommend,true);
if(!isset($recommend))
    header('Location: https://educateplus.000webhostapp.com');
$ratingMatrix =getRatingMatrix();
$allItems = getAllItems();
$allUsers = getAllUsers();
$itemsRated = getItemsRatedByUser($recommend['username']);
$meanItemRating = globalItemsMean();
$avgUserRating = avgUserRatings($recommend['username']);
function isValidRecommend($recommend)
{
	return $recommend['recommend']=='true';
}
function getItemsForUser($username,$size)
{
	global $con;
	//$users = getAllUsers();
	$sql ='SELECT rating,itemName FROM ratings where userName="'.$username.'" and rating != 0;';
	$result =$con->query($sql);
	while($row = mysqli_fetch_row($result))
	{	
		foreach ($row as $value)
		{
				$ratings[$row[1]]= round($row[0],1);
		}
	}
	arsort($ratings);
	$ratings=array_slice($ratings, 0,$size);
	//$ratings=array_keys($ratings);
	$ratings=json_encode($ratings);
	print_r($ratings);
	//return $ratings;
}

function predictionForSpeceficUser($size,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating )
{
	
		$notRatedItems =getItemsNotRatedByUser($user);
		$recommend=array();
		if(count($notRatedItems)==0)
		{
			getItemsForUser($user,$size);
		}
		else
		{
			for ($i = 0; $i < count($notRatedItems); $i++)
			{
				$prediction = prediction($size,$notRatedItems[$i],$ratingMatrix,$user,$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating );
				$recommend[$notRatedItems[$i]]=round($prediction,1);
			}
			arsort($recommend);
            foreach($recommend as $key => $value)
                if ($value < 2.5)
                    unset($recommend[$key]);
			$recommend=json_encode($recommend);
			print_r($recommend);
		}
		
}
function size($user)
{	
	$numItemsRated    = count(getItemsRatedByUser($user));
	$numItemsNotRated = count(getItemsNotRatedByUser($user));
	return (($numItemsNotRated==0)? $numItemsRated:$numItemsNotRated);
}

//send recommendations as json with this function

predictionForSpeceficUser(size($recommend['username']),$ratingMatrix,$recommend['username'],$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating );
//echo $recommend['username'];
//print_r(json_encode(Ksimilar(1,'Serie2Applicationslineaires.pdf',$ratingMatrix,$recommend['username'],$allItems,$itemsRated,$allUsers)));
