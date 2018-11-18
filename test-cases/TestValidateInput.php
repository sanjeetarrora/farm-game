<?php

Class TestValidateInput
{
    private $validate_input;

    public $input = [
        'eaters' => [
            'Farmer'=> 0,
            'Cow 1'=> 0,
            'Cow 2'=> 0,
            'Bunny 1'=> 0,
            'Bunny 2'=> 0,
            'Bunny 3'=> 0,
            'Bunny 4'=> 0,
        ]
    ];

    public function __constructor()
    {
        $this->validate_input = new FarmGame;
    }

    public function __destructor()
    {
        $this->validate_input = NULL;
    }

    public function testAdd()
    {
        $os = new FarmGame;
        $result = $os->validateInput($this->input);
        if ($result === true) echo 'Test Ok.';
        else echo 'Test Failed.';
    }

}