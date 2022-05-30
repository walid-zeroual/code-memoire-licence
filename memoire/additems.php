<?php 
include 'db.php';

$files=glob("items/*.*");
for($i = 0; $i <count($files) ; $i++)
{
	$files[$i]=str_replace('items/', '', $files[$i]);
}

function addItems($files)
{
    global $con;
    for($f=0;$f<count($files);$f++)
    {
        $query = "INSERT INTO items(itemName) VALUES ('".$files[$f]."');";
        $result = mysqli_query($con,$query);
        if($result)
            echo 'inserted successfully'."\n";
        else
            echo 'insertion failure!'."\n";
    }
}

//addItems($files);