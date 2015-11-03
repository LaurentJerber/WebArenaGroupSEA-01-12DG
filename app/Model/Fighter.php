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
   
   public function doMmove($fighterId, $direction) {
	   $fighter = $this -> findById($fighterId);
	   
		switch ($direction) {
		case "north":
			$fighter["Fighter"]["coordinate_y"]++;
			break;
		case "east":
			$fighter["Fighter"]["coordinate_x"]--;
			break;
		case "south":
			$fighter["Fighter"]["coordinate_y"]--;
			break;
		case "west":
			$fighter["Fighter"]["coordinate_x"]++;
			break;
		default:
			return false;
		}
	   
	   $this -> save($fighter);
	   return true;
	}
	
	public function getCoordinates($fighterId) {
		$fighter = $this -> findById($fighterId);
		return "X : " . $fighter['Fighter']['coordinate_x'] . " / Y : " . $fighter['Fighter']['coordinate_y'];
	}
}