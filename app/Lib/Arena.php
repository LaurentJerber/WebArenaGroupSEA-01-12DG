<?php

/*

Cette classe devrait etre un Model

*/

class Arena {
	public $arena;
	private $casesNumber;
	
	const SIZE_X = 15;
	const SIZE_Y = 10;
	
	const TAUX_DE_COLONNES = 0.1; //1 colonne pour 10 cases
	const TAUX_DE_PIEGES = 0.1; // 1 piège pour 10 cases
	const TAUX_DE_MONSTRES = 0.05; //1 monstre pour 20 cases
	const TAUX_OBJETS = 0.05; //1 objet (potion, lunettes, bouclier) pour 20 cases
	
	const FIGHTER = 'fighter';
	
	CONST DECOR_COLONNE = 'colonne';
	const DECOR_PIEGE = 'piege';
	const DECOR_MONSTRE = 'monstre';
	
	const OBJET_POTION_MAGIQUE = 'potion'; //Pour la force
	const OBJET_BOUCLIER = 'bouclier'; //Pour la santé
	const OBJET_JUMELLES = 'jumelles'; //Pour le sight
	const OBJET_BONUS = 1;
	
	public function __construct() {
		$this -> arena =  $this -> generateEmptyArena();
		$this -> casesNumber = self::SIZE_X * self::SIZE_Y;
		for ($y = 0; $y < self::SIZE_Y; $y++) {
			for($x = 0; $x < self::SIZE_X; $x++) {
				$this -> arena[$y][$x] = null;
			}
		}
	}
	
	public function setFighters($fighters) {
		foreach ($fighters as $fighter) {
			$f = $fighter['Fighter'];
			if ($f['realHealth'] > 0)
				$this -> arena[$f['coordinate_y']][$f['coordinate_x']] = self::FIGHTER;
		}
	}
	
	public function setSurroundings($surroundings) {
		foreach ($surroundings as $sur) {
			$s = $sur['Surrounding'];
			$this -> arena[$s['coordinate_y']][$s['coordinate_x']] = $s['type'];
		}
	}
	
	public function setTools($tools) {
		foreach ($tools as $tool) {
			$t = $tool['Tool'];
			if ($t['fighter_id'] == 0)
				$this -> arena[$t['coordinate_y']][$t['coordinate_x']] = $t['type'];
		}
	}
	
	public function createArena() {
		$this -> arena = $this -> generateEmptyArena();
		$surroundings = array();
		$tools = array();
		
		// GENERATION DES OBJETS
		$numberOfObjects = self::TAUX_OBJETS * $this -> casesNumber;
		$createdObjects = 0;
		while ($createdObjects < $numberOfObjects) {
			$rX = rand(0, self::SIZE_X - 1);
			$rY = rand(0, self::SIZE_Y - 1);
			$rObject = rand(0, 2);
			switch ($rObject) {
			case 0: $o = self::OBJET_BOUCLIER; break;
			case 1: $o = self::OBJET_JUMELLES; break;
			case 2: $o = self::OBJET_POTION_MAGIQUE; break;
			}
			
			if ($this -> arena[$rY][$rX] == null) {
				$this -> arena[$rY][$rX] = $o;
				$tools[] = array('type' => $o, 'coordinate_x' => $rX, 'coordinate_y' => $rY);
				$createdObjects++;
			}
		}
		
		// GENERATION DES COLONNES
		$numberOfColumns = self::TAUX_DE_COLONNES * $this -> casesNumber;
		$createdColumns = 0;
		while ($createdColumns < $numberOfColumns) {
			$rX = rand(0, self::SIZE_X - 1);
			$rY = rand(0, self::SIZE_Y - 1);
			
			if ($this -> arena[$rY][$rX] == null) {
				$this -> arena[$rY][$rX] = self::DECOR_COLONNE;
				$surroundings[] = array('type' => self::DECOR_COLONNE, 'coordinate_x' => $rX, 'coordinate_y' => $rY);
				$createdColumns++;
			}
		}
		
		// GENERATION DES COLONNES
		$numberOfTraps = self::TAUX_DE_PIEGES * $this -> casesNumber;
		$createdTraps = 0;
		while ($createdTraps < $numberOfTraps) {
			$rX = rand(0, self::SIZE_X - 1);
			$rY = rand(0, self::SIZE_Y - 1);
			
			if ($this -> arena[$rY][$rX] == null) {
				$this -> arena[$rY][$rX] = self::DECOR_PIEGE;
				$surroundings[] = array('type' => self::DECOR_PIEGE, 'coordinate_x' => $rX, 'coordinate_y' => $rY);
				$createdTraps++;
			}
		}
		
		// GENERATION DES MONSTRES INVISIBLES
		$numberOfMonsters = self::TAUX_DE_MONSTRES * $this -> casesNumber;
		$createdMonsters = 0;
		while ($createdMonsters < $numberOfMonsters) {
			$rX = rand(0, self::SIZE_X - 1);
			$rY = rand(0, self::SIZE_Y - 1);
			
			if ($this -> arena[$rY][$rX] == null) {
				$this -> arena[$rY][$rX] = self::DECOR_MONSTRE;
				$surroundings[] = array('type' => self::DECOR_MONSTRE, 'coordinate_x' => $rX, 'coordinate_y' => $rY);
				$createdMonsters++;
			}
		}
		
		return array('surroundings' => $surroundings, 'tools' => $tools);
	}
	
	public function getValidFighterPosition() {
		$need = true;
		$i = 0;
		while ($need) {
			$rX = rand(0, self::SIZE_X - 1);
			$rY = rand(0, self::SIZE_Y - 1);
			if ($this -> arena[$rY][$rX] == null) {
				$need = false;
				return array('coordinate_x' => $rX, 'coordinate_y' => $rY);
			}
			if ($i > 100) break;
			$i++;
		}
		return null;
	}
	
	public function deplacementPossible($fx, $fy, $x, $y) {
		if ($x < Arena::SIZE_X && $y < Arena::SIZE_Y) {
			if ($this -> arena[$y][$x] != null) {
				switch ($this -> arena[$y][$x]) {
				case self::DECOR_COLONNE:
					return false;
				default: 
					if ($fx+1 == $x XOR $fx-1 == $x XOR $fy-1 == $y XOR $fy+1 == $y)
						return true;
					else return false;
				}
			}
			return true;
		} return false;
	}
	
	public function generateEmptyArena() {
		$arena = array();
		for ($y = 0; $y < self::SIZE_Y; $y++) {
			$arena[$y] = array();
			for ($x = 0; $x < self::SIZE_X; $x++) {
				$arena[$y][$x] = null;
			}
		}
		return $arena;
	}
	
	public function atDistance($fx, $fy, $x, $y) {
		if ($fx >= $x)
			$dX = $fx - $x;
		else $dX = $x - $fx;
		
		if ($fy >= $y)
			$dY = $fy - $y;
		else $dY = $y - $fy;
		
		return $dX + $dY;
	}
	
	public function isVisible($sight, $fx, $fy, $x, $y) {
		$d = $this -> atDistance($fx, $fy, $x, $y);
		
		if ($d <= $sight) return true;
		return false;
	}
	
	public function visibleByFighter($sight, $fx, $fy) {
		$visible = array();
		for ($y = 0; $y < self::SIZE_Y; $y++) {
			for ($x = 0; $x < self::SIZE_X; $x++) {
				if ($this -> isVisible($sight, $fx, $fy, $x, $y))
					$visible[] = array('coordinate_x' => $x, 'coordinate_y' => $y);
			}
		}
		return $visible;
	}
	
	public function isMonster($x, $y) {
		if ($this -> arena[$y][$x] == self::DECOR_MONSTRE)
			return true;
		return false;
	}
	
	public function draw($fx, $fy, $sight) {
		$arena = array();
		for ($y = 0; $y < self::SIZE_Y; $y++) {
			$arena[$y] = array();
			for ($x = 0; $x < self::SIZE_X; $x++) {
				if ($this -> isVisible($sight, $fx, $fy, $x, $y)) {
					if ($fx == $x && $fy == $y)
						$arena[$y][$x] = array('src' => 'fighter', 'movable' => false, 'message' => 'Vous êtes ici.', 'submessage' => '', 'attaquable' => false);
					else {
						if ($this -> arena[$y][$x] != null) {
							switch ($this -> arena[$y][$x]) {
							case self::DECOR_COLONNE: 
								$arena[$y][$x] = array('src' => 'pillar', 'movable' => false, 'message' => '', 'submessage' => 'Le passage est bloqué', 'attaquable' => false); break;
							case self::DECOR_MONSTRE:
								if ($this -> atDistance($fx, $fy, $x, $y) == 1)
									$arena[$y][$x] = array('src' => 'monstre', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => 'Il y a une odeur etrange là bas..', 'attaquable' => true);
								else $arena[$y][$x] = array('src' => 'null', 'movable' => false, 'message' => '', 'submessage' => 'Il y a une odeur etrange là bas', 'attaquable' => false);	
								break;
							case self::DECOR_PIEGE:
								if ($this -> atDistance($fx, $fy, $x, $y) == 1)
									$arena[$y][$x] = array('src' => 'piege', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => 'Il y a des fissures sur le sol.', 'attaquable' => false);
								else $arena[$y][$x] = array('src' => 'null', 'movable' => false, 'message' => '', 'submessage' => 'Il y a des fissures sur le sol.', 'attaquable' => false);	
								break;
							case self::OBJET_BOUCLIER:
								if ($this -> atDistance($fx, $fy, $x, $y) == 1)
									$arena[$y][$x] = array('src' => 'bouclier', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => "C'est un bouclier, il augmente la vie du combattant.", 'attaquable' => false);
								else $arena[$y][$x] = array('src' => 'bouclier', 'movable' => false, 'message' => '', 'submessage' => "C'est un bouclier, il augmente la vie du combattant.",'attaquable' => false);
								break;
							case self::OBJET_JUMELLES:
								if ($this -> atDistance($fx, $fy, $x, $y) == 1)
									$arena[$y][$x] = array('src' => 'jumelles', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => "Ce sont des jumelles, il augmente la vue du combattant.", 'attaquable' => false);
								else $arena[$y][$x] = array('src' => 'jumelles', 'movable' => false, 'message' => '', 'submessage' => "Ce sont des jumelles, il augmente la vue du combattant.", 'attaquable' => false);
								break;
							case self::OBJET_POTION_MAGIQUE:
								if ($this -> atDistance($fx, $fy, $x, $y) == 1)
									$arena[$y][$x] = array('src' => 'potion', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => "C'est une potion, il augmente la force du combattant.",'attaquable' => false);
								else $arena[$y][$x] = array('src' => 'potion', 'movable' => false, 'message' => '', 'submessage' => "C'est une potion, il augmente la force du combattant.",'attaquable' => false);
								break;
							case self::FIGHTER:
								if ($this -> atDistance($fx, $fy, $x, $y) == 1)
									$arena[$y][$x] = array('src' => 'fighter_red', 'movable' => false, 'message' => 'Attaquer ce combattant ?', 'submessage' => '', 'attaquable' => true);
								else $arena[$y][$x] = array('src' => 'fighter_red', 'movable' => false, 'submessage' => "C'est un combattant ennemi", 'message' => '', 'attaquable' => false);
								break;
							}
						} else {
							if ($this -> atDistance($fx, $fy, $x, $y) == 1)
								$arena[$y][$x] = array('src' => 'null', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => "Il n'y a rien ici.", 'attaquable' => false);
							else $arena[$y][$x] = array('src' => 'null', 'movable' => false, 'message' => '', 'submessage' => "Il n'y a rien ici.", 'attaquable' => false);
						}
					}
				} else {
					if ($this -> atDistance($fx, $fy, $x, $y) == 1)
						$arena[$y][$x] = array('src' => 'fog', 'movable' => true, 'message' => 'Vous rendre ici ?', 'submessage' => 'On ne peut pas voir à travers le brouillard...', 'attaquable' => false);
					elseif ($this -> arena[$y][$x] == self::FIGHTER && $this -> atDistance($fx, $fy, $x, $y) == 1)
						$arena[$y][$x] = array('src' => 'fighter_red', 'movable' => true, 'message' => 'Attaquer ce combattant ?', 'submessage' => '', 'attaquable' => true);
					else
						$arena[$y][$x] = array('src' => 'fog', 'movable' => false, 'message' => '', 'submessage' => 'On ne peut pas voir à travers le brouillard...', 'attaquable' => false);	
				}
			}
		}
		
		return $arena;
	}
	
	public function at($x, $y) {
		return $this -> arena[$y][$x];
	}
}