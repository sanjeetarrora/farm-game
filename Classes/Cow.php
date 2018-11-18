<?php

Class Cow implements FarmPlay {

    // You can maintain this as private to hide the no of turns to death
    private $max_turn_count = 10;

    public $cur_turn_count = 0;

    /**
     * This function can be an example of polymorphism.
     * Where sharing an interface, the fucntions could behave
     * according to thier respective Class
     */
    public function checkIfDead() {
        if ($this->max_turn_count -1 <= $this->cur_turn_count)
            return true;
        else return false;
    }

}