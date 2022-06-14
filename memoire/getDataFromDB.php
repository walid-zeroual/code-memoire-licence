<?php 

include 'db.php';
function getAllUsers(){
	global $con;
	$sql ='SELECT DISTINCT userName FROM users;';
	$result = $con->query($sql);
	$user=array();
	$users=array();
	while($row = mysqli_fetch_assoc($result))
		$user[]=$row;
	foreach ($user as $key => $value) 
	{
    	foreach($value as $key1 => $value1)
        	$users[]=$value1;
	}
	return $users;
}
function getAllItems(){
	global $con;
	$sql ='SELECT DISTINCT itemName FROM items;';
	$result = $con->query($sql);
	$item=array();
	$items=array();
	while($row = mysqli_fetch_assoc($result))
		$item[]=$row;
	foreach ($item as $key => $value) 
	{
    	foreach($value as $key1 => $value1)
        	$items[]=$value1;
	}
	return $items;
}
function getRatings(){
	global $con;
	$sql ='SELECT * FROM ratings';
	$result = $con->query($sql);
	$rating = array();
	$ratings = array();
	while($row=mysqli_fetch_row($result))
		$rating[]=$row;
	
	return $rating;

}
function checkUserExist($user){
	global $con;
	$sql='SELECT COUNT(1) FROM users WHERE userName = "'.$user.'";';
	$result = $con->query($sql);
	$result = mysqli_fetch_row($result);
	return $result[0];
}
function allowLogin($user='', $password='',$email=''){
	global $con;
	
		$sql='SELECT COUNT(1) FROM users WHERE userName = "'.$user.'";';
		$userCheck = $con->query($sql);
		$userCheck = mysqli_fetch_row($userCheck);

	
		$sql='SELECT COUNT(1) FROM users WHERE password = "'.md5($password).'";';
		$passwordCheck = $con->query($sql);
		$passwordCheck = mysqli_fetch_row($passwordCheck);
	

	
		$sql='SELECT COUNT(1) FROM users WHERE email = "'.$email.'";';
		$emailCheck = $con->query($sql);
		$emailCheck = mysqli_fetch_row($emailCheck);
	

	return ( $userCheck[0] and $passwordCheck[0] ) or ( $emailCheck[0] and $passwordCheck[0] );
}

function getRatingMatrix(){
	global $con;
	$sql = 'SELECT * FROM ratings ORDER BY itemName';//Requête sql pour tout selectionner de la table "ratings"
	$result=$con->query($sql);//lancement de la requête
	$userName = array();//tableau des noms des utilisateurs
	$itemName = array();//tableau des noms des articles
	$rating   = array();//tableau des notes
	$ratingMatrix = array();//matrice des notes de Articles*Utilisateurs
	while($row = mysqli_fetch_row($result))	
	{
		$userName[]=$row[0];
		$itemName[]=$row[1];
		$rating[]  =$row[2];
	}
	$userName = array_unique($userName);
	$itemName = array_unique($itemName);
	$r=0;
	$nbItems = count($itemName);
	foreach ($itemName as $item )
	{
		$i = 0;
		foreach($userName as $user)
		{
			$ratingMatrix[$item][$user]=$rating[$r+$i];	//création d'un tableau associatif Article=>Utilisateur=>Note
			$i+= $nbItems;
		}
		$r++;
	}
	return $ratingMatrix;
		
}
function getUserRatings($username)
{
	global $con;
	$sql ='SELECT rating FROM ratings where userName="'.$username.'";';
	$result =$con->query($sql);
	while($row = mysqli_fetch_row($result))
		$ratings[]=$row[0];
	
	return $ratings;
}
function getItemRatings($itemname)
{
	global $con;
	
	$sql ='SELECT userName,rating FROM ratings where itemName="'.$itemname.'";';
	$result =$con->query($sql);
	while($row = mysqli_fetch_row($result))
	{
		$ratings[$row[0]]=$row[1];
		
	}
	
	return $ratings;
}
function getItemsRatedByUser($username)
{
	global $con;
	
	$sql ='SELECT itemName FROM ratings where userName="'.$username.'" and rating != 0;';
	$result =$con->query($sql);
	$i=0;
	while($row = mysqli_fetch_row($result))
	{
		$ratings[$i]=$row[0];
		$i++;
	}
	
	return $ratings;
}
function getItemsNotRatedByUser($username)
{
	global $con;
	
	$sql ='SELECT itemName FROM ratings where userName="'.$username.'" and rating = 0;';
	$result =$con->query($sql);
	$i=0;
	$ratings = array();
	while($row = mysqli_fetch_row($result))
	{
		$ratings[$i]=$row[0];
		$i++;
	}
	
	return $ratings;
}

function avgUserRatings($username)
{
	global $con;
	$sql = "SELECT rating FROM ratings where userName='".$username."' AND rating!=0;";
	try
	{
		$result = $con->query($sql);
	}
	catch(Exception $e)
	{
		return -1;
	} 
	$rating=0;
	$nbItems=0;
	while($row = mysqli_fetch_row($result))
	{
		$rating+=$row[0];
		$nbItems++;
	}
	$rating/=$nbItems;
	return $rating;
}
function globalItemsMean()
{
	global $con;
	$sql ="SELECT rating from ratings where rating != 0";
	try
	{
		$result = $con->query($sql);
	}
	catch(Exception $e)
	{
		return -1;
	}
	$mean =0;
	$nbItems=0;
	while($row = mysqli_fetch_row($result))
	{
		$mean+=$row[0];
		$nbItems++;
	}
	$mean /= $nbItems;
	return round($mean,2);
}
function avgItemRatings($itemname)
{
	global $con;
	$sql = "SELECT rating FROM ratings where itemName='".$itemname."' AND rating!=0;";
	try
	{
		$result = $con->query($sql);
	}
	catch(Exception $e)
	{
		return -1;
	} 
	$rating=0;
	$nbItems=0;
	while($row = mysqli_fetch_row($result))
	{
		$rating+=$row[0];
		$nbItems++;
	}
	$rating/=$nbItems;
	return $rating;
}
?>