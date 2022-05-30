<?php 

include 'getDataFromDB.php';

function addUser($username,$email,$password)
{
	global $con;
	$username = stripslashes($username);
    $username = mysqli_real_escape_string($con, $username);
    $email    = stripslashes($email);
    $email    = mysqli_real_escape_string($con, $email);
    $password = stripslashes($password);
    $password = mysqli_real_escape_string($con, $password);
    $create_datetime = date("Y-m-d H:i:s");
	$query = "INSERT INTO users(userName,password,email,create_datetime) VALUES ('$username', '" . md5($password) . "', '$email', '$create_datetime');";
	$result = mysqli_query($con,$query);
	if($result)
	{
		echo 'You are registered successfully';
	}
	else
	{
		echo 'something wrong happend';
	}
}

//addUser('test1','test1','123456');

function addNewUserToRatingsTableWithZeroRatingForAllItems($username,$items){
	global $con;
	for($i=3;$i<count($items);$i++)
	{
		$sql = "INSERT INTO ratings(userName,itemName,rating)
		VALUES ('".$username."','".$items[$i]."',(FLOOR(RAND()*(5)+1))); ";
	
	    $result = mysqli_query($con, $sql);
		
	}
}
//addNewUserToRatingsTableWithZeroRatingForAllItems('test1',getAllItems());
//(FLOOR(RAND()*(5)+1))