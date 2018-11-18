<?php

abstract Class Farm {
    // Ones to be fed in a farm
    public $farm_entities = [
        'Farmer','Cow 1','Cow 2','Bunny 1','Bunny 2','Bunny 3','Bunny 4'
    ];

    // Plus minus the values to maintain min win ones required
    public $farm_win_entities = ['Farmer','Cow','Bunny'];

    // On this fellow death, game ends
    public $farmer_title = 'Farmer';

    // Animals in the farm
    public $farm_animals = ['Cow', 'Bunny'];

    /**
     * Fetch the Game Details here to play
     * This gives the initial setup config for the UI
     */
    public function sendGameDetails() {
        $data = [
            'farmEntities' => $this->farm_entities,
            'farmWinEntities' => $this->farm_win_entities,
            'totalturnCount' => $this->total_turn_count,
            'farmerTitle' => $this->farmer_title
        ];
        echo json_encode($data);
        exit;
    }

    // Chooses the one to be fed
    public function randomMemberToBeFed() {
        $only_eaters_arr = array_keys($this->eaters_turn_count);
        $get_rand_eater_key = array_rand($only_eaters_arr);
        $this->fed_to = $only_eaters_arr[$get_rand_eater_key];
    }

}