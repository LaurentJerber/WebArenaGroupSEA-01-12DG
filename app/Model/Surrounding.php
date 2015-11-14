<?php

App::uses('AppModel', 'Model');

App::uses('Arena', 'Lib');

class Surrounding extends AppModel {
	
    public $displayField = 'name';

    public $belongsTo = array(
	);
   
    public function setArena($arena) {
		$acceptedTypes = array(Arena::DECOR_COLONNE, Arena::DECOR_MONSTRE, Arena::DECOR_PIEGE);
		if ($arena instanceof Arena) {
			$toSave = array();
			foreach ($arena -> arena as $y => $line) {
				foreach ($line as $x => $element) {
					if (in_array($element, $acceptedTypes)){
						$this -> query("INSERT INTO surrounding (type, coordinate_x, coordinate_y) VALUES ('" . $element . "', '" . $x . "', '" . $y . "')");
					}
				}
			}
		}
	}
	
	public function getSurroundingAt($x, $y) {
		//Je récupère ça a la mano, quand je fais ça de la même façon que pour getFighterAt ça me met une erreur SQL sur coordinate_x
		if (intval($x) && intval($y)) { //pour éviter toute injection SQL
			$surrounding = $this -> query("SELECT * FROM surrounding WHERE coordinate_x = " . $x . " AND coordinate_y = " . $y);
		}
		return $surrounding[0]['surroundings'];
	}
	
	public function deleteMonster($x, $y) {
		$monster = $this -> find('first', array('conditions' => array('coordinate_x = ' => $x, 'coordinate_y = ' => $y)));
		if ($monster) {
			$this -> delete($monster['Surrounding']['id']);
			return true;
		} return false;
	}
	
   	public function truncate() {
		$this -> query("TRUNCATE surroundings");
	}
} ?>