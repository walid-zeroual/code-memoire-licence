<?php 	
//parameters should be changed according to your settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'test');//create database manualy first 

$connect = mysqli_connect(DB_HOST ,DB_USER ,DB_PASS ,DB_NAME);

$userSQL = "CREATE TABLE if not exists users (
   			userName varchar(55),
   			Email varchar(55),
    		Password varchar(55),
  			Creation_date datetime
			);";


$itemSQL = "CREATE TABLE if not exists items (itemName varchar(255));";

$ratingSQL = "CREATE TABLE if not exists ratings(
				userName varchar(55),
				itemName varchar(255),
				rating int(1)
			);";	
$connect->query($userSQL);
$connect->query($itemSQL);
$connect->query($ratingSQL);

