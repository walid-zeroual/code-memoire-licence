<?php 
session_start();
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include'db.php';

$login = file_get_contents('php://input');
$login = json_decode($login,true);

function isValidLogin($loginJson){
    return $loginJson['login']=='true';
}

function getRightLogins($loginJson)
{
    global $con;
    $username = mysqli_real_escape_string($con,stripslashes($loginJson['username']));
    $password = mysqli_real_escape_string($con,stripslashes($loginJson['password']));
    $email    = mysqli_real_escape_string($con,stripslashes($loginJson['email']));
    $userNameQuery    = "SELECT userName FROM `users` WHERE email='$email'
                         AND password='" . md5($password) . "'";
    try
    {
        $userNameQuery = mysqli_query($con, $userNameQuery);
        $userNameQuery = mysqli_fetch_row($userNameQuery);
    }
    catch(Exception $e)
    {
        $userNameQuery = false;
    }
    
    if($userNameQuery)
    {
        $username = $userNameQuery[0];
        $loginJson['username']= $username;
    }
    else
    {
        $emailQuery = "SELECT email FROM `users` WHERE username='$username'
                       AND password='" . md5($password) . "'";
        try 
        {
            $emailQuery = mysqli_query($con, $emailQuery);
            $emailQuery =mysqli_fetch_row($emailQuery);
        }
        catch(Exception $e)
        {
            $emailQuery =false;
        }
        if($emailQuery)
        {
            $email=$emailQuery[0];
            $loginJson['email']= $email;


        }

    }
    return $loginJson;

}
function loginUser($loginJson){
    $userNameRows = false;
    $emailRows    = false;
    if(isValidLogin($loginJson)){
        global $con;
        
        $username = mysqli_real_escape_string($con,stripslashes($loginJson['username']));
        $password = mysqli_real_escape_string($con,stripslashes($loginJson['password']));
        $email    = mysqli_real_escape_string($con,stripslashes($loginJson['email']));
        $userNameQuery    = "SELECT * FROM `users` WHERE userName='$username'
                    AND password='" . md5($password) . "'";
        try
        {
            $userNameResult = mysqli_query($con, $userNameQuery);
            $userNameRows = mysqli_num_rows($userNameResult);
        }
        catch(Exception $e)
        {
            $userNameResult = false;
            $userNameRows   = false;
        }
        if ($userNameRows == 1) {

        }
        $emailQuery    = "SELECT * FROM `users` WHERE email='$email'
                    AND password='" . md5($password) . "'";
        try
        {
            $emailResult = mysqli_query($con, $emailQuery);
            $emailRows   = mysqli_num_rows($emailResult);
        }
        catch (Exception $e)
        {
            $emailResult = false;
            $emailRows   = false;
        }
        if ($emailRows == 1) {

        }
    }
    return $userNameRows or $emailRows;
}

function setLoginJson($loginJson){
    if(loginUser($loginJson)){
        http_response_code(200);
        $loginJson=getRightLogins($loginJson);
        $_SESSION[$loginJson['username']]='loggedIn';
        session_write_close();
        $response =json_encode($loginJson,true);
        print_r($response);
    }
    else
    {
        http_response_code(200);
        $loginJson['login']='false';
        $response =json_encode($loginJson,true);;
        print_r($response);
    }
}
//uncomment this to send login information as json
setLoginJson($login);
