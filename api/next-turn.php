<?php

/**
 * Not the right place for these headers,
 * but as no common route keeping it here.
 * In a bigger application, would be in the main entry file where all
 * hits to application are recieved and are further redirected ahead.
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$input = $_POST;

// Autoload Classes
spl_autoload_register('myAutoloader');
function myAutoloader($class_name)
{
    require_once '../Classes/' . $class_name.'.php';
}

$game_obj = new FarmGame;

// Validate input 
if ($game_obj->validateInput($input)) {

    /**
     * Skill: SOLID Principles
     * make sure each function
     * performs only 1 task
     */

    /**
     * Calculate the current scenario only.
     * This function can be used if further to be played by command line
     */
    $game_obj->playTurn();

    // set messages for the round
    $game_obj->setRoundMessages();

    // Separated response module function
    $game_obj->endCurrentTurn();
}
else {
    $game_obj->invalidInput();
}