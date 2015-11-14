<?php

App::uses('AppModel', 'Model');

App::uses('Arena', 'Lib');

class Tool extends AppModel {
	
    public $displayField = 'name';

    public $belongsTo = array(
	 'Fighter' => array(

            'className' => 'Fighter',
            'foreignKey' => 'fighter_id',

        )
	);
   
   
    public function setArena($arena) {
		$acceptedTypes = array(Arena::OBJET_POTION_MAGIQUE, Arena::OBJET_BOUCLIER, Arena::OBJET_JUMELLES);
		if ($arena instanceof Arena) {
			$toSave = array();
			foreach ($arena -> arena as $y => $line) {
				foreach ($line as $x => $element) {
					if (in_array($element, $acceptedTypes)){
						$this -> query("INSERT INTO tools (type, fighter_id, bonus, coordinate_x, coordinate_y) VALUES ('" . $element . "', 0, '" . Arena::OBJET_BONUS . "', '" . $x . "', '" . $y . "')");
					}
				}
			}
		}
	}
	
	public function getToolAt($x, $y) {
		//Je récupère ça a la mano, quand je fais ça de la même façon que pour getFighterAt ça me met une erreur SQL sur coordinate_x
		if (intval($x) && intval($y)) { //pour éviter toute injection SQL
			$tool = $this -> query("SELECT * FROM tools WHERE coordinate_x = " . $x . " AND coordinate_y = " . $y);
		}
		return $tool[0]['tools'];
	}
	
	public function changeFighter($tool, $fighterId) {
		$tool['fighter_id'] = $fighterId;
		$this -> save(array('Tool' => $tool));
	}
	
	public function getNumberOfTools($type, $fighterId) {
		return $this -> find('count', array('conditions' => array('type = ' => $type, 'fighter_id' => $fighterId)));
	}
	
	public function assign($fighterId, $x, $y) {
		$tool = $this -> getToolAt($x, $y);
		if ($tool) {
			$tool['fighter_id'] = $fighterId;
			$this -> save(array('Tool' => $tool));
		}
	}
	
   	public function truncate() {
		$this -> query("TRUNCATE tools");
	}
} ?>