<?php   
header('Content-Type: application/json; charset=utf-8');
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


include 'getDataFromDB.php';
$request = file_get_contents('php://input');

//print_r(json_encode(apache_request_headers()));
$request = json_decode($request,true);
//print_r(($_SERVER));

function itemAVG($request)
{
	global $con;
	//$users = getAllUsers();
	$sql ='SELECT itemName,rating FROM ratings where username="'.$request['username'].'" ;';
	$result =$con->query($sql);
	
	while($row = mysqli_fetch_row($result))
	{
		$ratings[$row[0]]=$row[1];
		
	}
	//print_r($ratings);
	arsort($ratings);
	return $ratings;
}

function isValidGetItems($request)
{
    if($request['getitems'])
        return $request['getitems']=='true';
    return false;
}
function sendItems($request)
{
	if($request and isValidGetItems($request) and isset($request['username'])){
	if($request['username']=='student')
	{
		$items = getAllItems();
		$tmp = array();
		foreach ($items as $key => $value) {
			$tmp[$value]=$value;
		}

		print_r(json_encode($tmp,true));
	}
else
    {
        print_r(json_encode(itemAVG($request),true));
    }
	}
}
//comment this to stop send items
sendItems($request);
