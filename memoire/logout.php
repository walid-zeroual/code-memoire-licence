<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$logout = file_get_contents('php://input');
$logout = json_decode($logout,true);


function isValidLogout($logoutJson)
{
    return $logoutJson['logout']=='true';
}

function logout($logoutJson)
{
    if(isValidLogout($logoutJson) /*and $_SESSION[$logoutJson['username']]=='loggedIn'*/)
    {
        try
        {
            $unset = session_unset();
        }
        catch (Exception $e)
        {
            echo 'unable to unset session  unset value = ';
            var_dump($unset);
        }
        try
        {
            $destroy = session_destroy();
        }
        catch (Exception $e)
        {
            echo 'unable to destroy session  destroy value = ';
            var_dump($destroy);
        }
        if($destroy)
        {
            $logoutJson=json_encode($logoutJson);
            print_r($logoutJson);
        }
        else
        {
            $logoutJson['logout']=='false';
            $logoutJson=json_encode($logoutJson);
            print_r($logoutJson);
        }
    }
    
}

logout($logout);
?>