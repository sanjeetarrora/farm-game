<?php

Class Farmer implements FarmPlay {

    private $max_turn_count = 15;

    public $cur_turn_count = 0;

    public function checkIfDead() {
        if ($this->max_turn_count -1 <= $this->cur_turn_count)
            return true;
        else return false;
    }

}