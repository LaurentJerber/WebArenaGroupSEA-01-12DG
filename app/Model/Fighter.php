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
   
	public function getFighter($id) {
		$fighter = $this ->  findById($id);
		if ($fighter)
			return $fighter['Fighter'];
		else return false;
	}
	
	public function getRealFighters($tools) {
		$fighters = $this -> find('all');
		$nbFighters = count($fighters);
		for ($i = 0; $i < $nbFighters; $i++) {
			$nbShields = $this -> numberOfTools($fighters[$i]['Fighter']['id'], Arena::OBJET_BOUCLIER, $tools);
			$fighters[$i]['Fighter']['realHealth'] = $fighters[$i]['Fighter']['current_health'] + ($nbShields * Arena::OBJET_BONUS);
		}
		return $fighters;
	}
	
	public function numberOfTools($id, $type, $tools) {
		$nb = 0;
		foreach ($tools as $t) {
			if ($t['Tool']['fighter_id'] == $id && $t['Tool']['type'] == $type) $nb++;
		}
		return $nb;
	}
	
	public function getFightersOf($playerId) {
		$fighters = $this ->  find('all', array('conditions' => array('player_id = ' => $playerId)));
		if ($fighters)
			return $fighters;
		else return false;
	}
	
	public function getFighterAt($x, $y) {
		$fighter = $this -> find('first', array('conditions' => array('coordinate_x = ' => $x, 'coordinate_y = ' => $y)));
		return $fighter['Fighter'];
	}
	
	public function getFighterByPlayer($id) {
		$fighter = $this -> find('first', array('conditions' => array('player_id = ' => $id)));
		return $fighter['Fighter'];
	}
	
	public function getLevel($fighter) {
		return floor($fighter['level'] / 4);
	}
	
	public function dead($id) {
		$fighter = $this -> getFighter($id);
		if ($fighter) {
			$fighter['current_health'] = -200;
			$this -> save(array('Fighter' => $fighter));
		}
	}
}