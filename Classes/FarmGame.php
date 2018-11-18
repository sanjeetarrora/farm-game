<?php

Class FarmGame extends Farm {

    public $eaters_turn_count = [];
    public $turn_count = 0; // this is to maintain each turn count
    public $total_turn_count = 50; // max turns
    public $fed_to;
    public $alive = []; // Ones who survive the turn

    // Display status and message as per the current scenario
    public $game_msg = [
        'msg' => [],
        'status' => 'Play again'
    ];

    // Validate the input
    public function validateInput($input) {
        // In php 7.0 you can write i.e. isset($input['eaters_turn_count']) ?? null;
        $this->eaters_turn_count = isset($input['eaters']) ? $input['eaters']: null;

        /*
        Note: I am not sending turnCount first time
        TurnCount is set after the first requset and
        then the same server turncount is used for all operations
        even incrementing
        */
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

    // calculate or set the current game scenario as per the UI or frontend
    public function playGame() {
        $this->feedRandomFarmMember();
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