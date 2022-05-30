<?php 

include 'getDataFromDB.php';

function addRatings($users,$files)
{
	global $con;
	for($j=0;$j<count($users);$j++)
        for($i=0;$i<count($files);$i++){
            $sql = "INSERT INTO ratings(userName,itemName,rating) VALUES ('".$users[$j]."','".$files[$i]."',0) ;";
            if($con->query($sql) === TRUE)
       			echo "insert success"."\n";
        	else 
        		echo "insert fail".$con->error."\n";
        }
}


//addRatings(getAllUsers(),getAllItems());

//randomizeRatings();
