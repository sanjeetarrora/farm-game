<?php

/*
Not the right place for these headers, but as no common routes have written here in a common place.
In a bigger application, would be in the main entry file where all 
hits to application are recieved and are further redirected ahead.
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


// Autoload Classes
spl_autoload_register('myAutoloader');
function myAutoloader($class_name)
{
    require_once 'Classes/' . $class_name.'.php';
}

$game_obj = new FarmGame;

$game_obj->sendGameDetails();

exit;