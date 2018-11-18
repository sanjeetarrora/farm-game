<?php

// Autoload Classes
spl_autoload_register('myAutoloader');
function myAutoloader($class_name)
{
    if (strpos($class_name, 'Test') !== false)
        require_once $class_name.'.php';
    else
        require_once __DIR__.'/../Classes/' . $class_name.'.php';
}

switch ($argv[1]) {
    case 'validate-input':
        $obj = new TestValidateInput;
        $obj->testAdd();

        break;
    default:
        
        # code...
        break;
}
