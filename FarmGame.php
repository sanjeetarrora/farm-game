<?php

Class FarmGame {

    public $eaters_turn_count = [];
    public $turn_count = 0; // this is to maintain each turn count
    public $total_turn_count = 50; // max turns
    public $fed_to;

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

    // Display status and message as per the current scenario
    public $game_msg = [
        'msg' => [],
        'status' => 'Play again'
    ];

    // Ones who survive the turn
    public $alive = [];

    // Fetch the Game Details here to play
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

    // Validate the input
    public function validateInput($input) {
        // In php 7.0 you can write i.e. isset($input['eaters_turn_count']) ?? null;
        $this->eaters_turn_count = isset($input['eaters']) ? $input['eaters']: null;
        $this->turn_count = isset($input['turnCount']) ? $input['turnCount']: 0;

        if (isset($this->eaters_turn_count[$this->farmer_title]) 
            && $this->turn_count >= 0 && $this->turn_count < 50) {

            foreach ($this->eaters_turn_count as $entity_name => $ent_turn_count) {

                $entity_name_arr = explode(' ',ucfirst(strtolower($entity_name)));
                if ($entity_name_arr[0] != $this->farmer_title
                 && !in_array($entity_name_arr[0], $this->farm_animals))
                    return false;
            }
            return true;
        }
        return false;
    }

    // Chooses the one to be fed
    public function feedRandomFarmerMember() {
        $only_eaters_arr = array_keys($this->eaters_turn_count);
        $get_rand_eater_key = array_rand($only_eaters_arr);
        $this->fed_to = $only_eaters_arr[$get_rand_eater_key];
    }

    // calculate or get the current game scenario as per the UI or frontend
    public function playGame() {
        $this->feedRandomFarmerMember();
        foreach ($this->eaters_turn_count as $entity_name => $ent_turn_count) {

            // Convert to lowercase
            // Then first letter to uppercase
            // Then break string to array
            $entity_name_arr = explode(' ',ucfirst(strtolower($entity_name)));
            $entity = $entity_name_arr[0];
            $name = '';
            if ($entity != $this->farmer_title)
                $name = '_' . $entity_name_arr[1];

            // Create player objects
            $obj_name = $entity . $name . '_obj';
            $$obj_name = new $entity();
            $$obj_name->cur_turn_count = $ent_turn_count;

            if ($this->fed_to !== $entity_name) {
                // if ($$obj_name->max_turn_count -1 <= $ent_turn_count) {
                if ($$obj_name->checkIfDead()) $this->death($entity_name);
                else $this->maintainAlive($entity_name, $entity);
            }
            else $this->feed($entity_name, $entity);
        }

        ++$this->turn_count; // increment every turn

        // Check if won
        if ($this->turn_count == 50) $this->checkIfWon();
    }

    public function death($entity_name) {
        unset($this->eaters_turn_count[$entity_name]);
        $this->game_msg['msg'][] = 'Dead: '. $entity_name;
        if ($entity_name == $this->farmer_title)
            $this->game_msg['status'] = 'Game Over';
    }

    public function feed($entity_name, $entity) {
        $this->alive[] = $entity;
        $this->game_msg['msg'][] = 'Fed: '. $entity_name;
        $this->eaters_turn_count[$this->fed_to] = 0;
    }

    public function maintainAlive($entity_name, $entity) {
        $this->alive[] = $entity; // maitain the ones who are alive
        ++$this->eaters_turn_count[$entity_name];
    }

    public function checkIfWon() {
        /*
        Might need to uncomment this below if statement
        if this function is called at multiple places
        */
        // if ($this->turn_count == 50) {
        if (empty(array_diff($this->farm_win_entities,array_unique($this->alive))))
            $this->game_msg['status'] = 'You win.';
        else $this->game_msg['status'] = 'Game Over';
        // }
    }

    // send response for the particular scenario
    public function endCurrentTurn() {
        $data = [
            'eaters' => $this->eaters_turn_count,
            'turnCount' => $this->turn_count,
            'message' => $this->game_msg
        ];
        echo json_encode($data);
        exit;
    }

    public function invalidInput() {
        $data = [
            'status' => 'Invalid Input'
        ];
        echo json_encode($data);
        exit;
    }
}