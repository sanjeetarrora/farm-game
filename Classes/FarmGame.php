<?php

/**
 * Extending Farm Class
 * I am inheriting the properties and methods
 * of Farm Class
 */
Class FarmGame extends Farm {

    public $eaters_turn_count = [];
    public $turn_count = 0; // this is to maintain each turn count
    public $total_turn_count = 50; // max turns
    public $fed_to; // current one to be fed
    public $c_alive = []; // categories who survived the turn
    public $alive = []; // ones who survived the turn (not in use currently)
    public $dead = []; // Ones who died on the turn

    // Display status and message as per the current scenario
    public $game_msg = [
        'msg' => [],
        'status' => 'Play again'
    ];

    // Validate the input
    public function validateInput($input) {
        // In php 7.0 you can write i.e. isset($input['eaters_turn_count']) ?? null;
        $this->eaters_turn_count = isset($input['eaters']) ? $input['eaters']: null;

        /**
         * Note: I am not sending turnCount the first turn
         * TurnCount is set after the first turn and
         * then the same server turnCount is used for all operations
         * even after incrementing
         */
        $this->turn_count = isset($input['turnCount']) ? $input['turnCount']: 0;

        // check if farmer and turncount has right values
        if (isset($this->eaters_turn_count[$this->farmer_title]) 
            && $this->turn_count >= 0 && $this->turn_count < 50) {

            foreach ($this->eaters_turn_count as $entity_name => $ent_turn_count) {
                $entity_name_arr = explode(' ',ucfirst(strtolower($entity_name)));

                // check if other members are farm animals
                if ($entity_name_arr[0] != $this->farmer_title
                 && !in_array($entity_name_arr[0], $this->farm_animals))
                    return false;
            }
            return true;
        }
        return false;
    }

    // calculate or set the current game scenario as per the UI or frontend
    public function playTurn() {
        $this->randomMemberToBeFed();
        foreach ($this->eaters_turn_count as $entity_name => $ent_turn_count) {
            /**
             * PHP Skill: use native functions
             * Convert to lowercase
             * Then first letter to uppercase
             * Then break string to array
             */
            $entity_name_arr = explode(' ',ucfirst(strtolower($entity_name)));
            $entity = $entity_name_arr[0];
            $name = '';
            if ($entity != $this->farmer_title)
                $name = '_' . $entity_name_arr[1];

            /**
             * Skill: Use Factory Design Pattern
             * Based on the Farm Member
             * that particular farm member class is called
             * for operation specific to it
             */
            // Create player objects
            $obj_name = $entity . $name . '_obj';
            $$obj_name = new $entity();
            $$obj_name->cur_turn_count = $ent_turn_count;

            if ($this->fed_to !== $entity_name) {
                if ($$obj_name->checkIfDead()) $this->death($entity_name);
                else $this->maintainAlive($entity_name, $entity);
            }
            else $this->feed($entity_name, $entity);
        }

        ++$this->turn_count; // increment every turn
    }

    public function death($entity_name) {
        unset($this->eaters_turn_count[$entity_name]);
        $this->dead[] = $entity_name;
    }

    public function feed($entity_name, $entity) {
        $this->c_alive[] = $entity;
        $this->eaters_turn_count[$this->fed_to] = 0;
    }

    public function maintainAlive($entity_name, $entity) {
        $this->c_alive[] = $entity; // maitain the ones who are alive
        ++$this->eaters_turn_count[$entity_name];
    }

    public function checkIfWon() {
        if ($this->turn_count == $this->total_turn_count) {

            /**
             * PHP Skill: use native functions
             * Categories could be duplicated in operations
             * So made sure they are unique
             * By checking the difference in arrays
             * making sure min win condition is accomplished
             */
            if (empty(array_diff($this->farm_win_entities,
                array_unique($this->c_alive))))
                $this->game_msg['status'] = 'You win.';
            else $this->game_msg['status'] = 'Game Over';
        }
    }

    public function deathMessages() {
        foreach ($this->dead as $entity_name) {
            $this->game_msg['msg'][] = 'Dead: '. $entity_name;
            if ($entity_name == $this->farmer_title)
                $this->game_msg['status'] = 'Game Over';
        }
    }

    public function setRoundMessages() {
        $this->game_msg['msg'][] = 'Fed: '. $this->fed_to; // who was fed
        $this->checkIfWon(); // Check if won
        $this->deathMessages(); // The ones who died
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