<?php

App::uses('AppModel', 'Model');

class Fighter extends AppModel {
	
    public $displayField = 'name';

    public $belongsTo = array(

        'Player' => array(

            'className' => 'Player',

            'foreignKey' => 'player_id',

        ),

   );
   
	public function doMove($fighterId, $direction){
		$fighter = $this -> findById($fighterId);
		
		switch ($direction) {
		case "north": $fighter['Fighter']['coordinate_y']++; break;
		case "south": $fighter['Fighter']['coordinate_y']--; break;
		case "west": $fighter['Fighter']['coordinate_x']++; break;
		case "east": $fighter['Fighter']['coordinate_x']--; break;
		}
		
		$this -> save($fighter);
	}
	
	public function pouet() {
		
	}
	
	public function getCoordinates($fighterId) {
		$fighter = $this -> findById($fighterId);
		return "X : " . $fighter['Fighter']['coordinate_x'] . " / Y : " . $fighter['Fighter']['coordinate_y'];
	}
}