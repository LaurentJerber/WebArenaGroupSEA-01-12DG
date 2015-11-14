<?php 

App::uses('AppController', 'Controller');
App::import('Vendor', 'Facebook', array('file' => 'Facebook' . DS . 'autoload.php'));
App::uses('FileTools', 'Lib');
App::uses('FileUploader', 'Lib');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
/**
 * Main controller of our small application
 *
 * @author ...
 */
class ArenasController extends AppController
{
	public $components = array('Session');
	
	public $uses = array('Player', 'Fighter', 'Event', 'Surrounding', 'Tool');
    /**
     * index method : first page
     *
     * @return void
     */
    public function index()
    {
		$this -> set('title', 'Webarena');
		$this -> set('myname', "Laurent Jerber");
    }
	
	public function login() {
		$this -> set('title', 'Connexion - Webarena');
		if ($this -> Session -> read('playerId'))
			$this -> Session -> delete('playerId');
		
		if (!$this -> Session -> read('playerId')) {
			if (isset($this -> request -> params['named']['password'])) {
				if (isset($this -> request -> data['reinitPw']['email'])) {
					$player = $this -> Player -> getByEmail($this -> request -> data['reinitPw']['email']);
					if ($player) {
						$uniqueTemplate = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
						$uniqueTemplate = str_split($uniqueTemplate);
						$templateSize = count($uniqueTemplate);
						$unique = '';
						for ($i = 0; $i < 64; $i++) {
							$unique .= $uniqueTemplate[rand(0, $templateSize-1)];
						}
						pr($unique);
						$player['password'] = $unique;
						$this -> Player -> save(array('Player' => $player));
						$this -> set('emailSent', true);
						$this -> set('unique', $unique);
						$this -> set('playerId', $player['id']);
					}
				} else $this -> set('sendEmail', true);
			}
			
			if (isset($this -> request -> params['named']['playerid'])) {
				if (isset($this -> request -> params['named']['u'])) {
					$player = $this -> Player -> getById($this -> request -> params['named']['playerid']);
					if (isset($this -> request -> data['pw'])) {
						$form = $this -> request -> data;
						if ($form['pw'] == $form['pwc']) {
							$player['password'] = md5($form['pw']);
							$this -> Player -> save(array('Player' => $player));
							$this -> set('passwordChanged');
						} else $this -> set('error', 'Erreur : Les mots de passe ne correspondent pas');
					} else {
						if ($player['password'] == $this -> request -> params['named']['u']){
							$this -> set('pickNewPassword', true);
						} else $this -> set('error', 'Erreur : Le lien est érroné');
					}
				}
			}
			
			if (isset($this -> request -> data['login'])) {
				$form = $this -> request -> data;
				if (isset($form['login'])) {
					if ($this -> Player -> existEmail($form['login']['email'])) {
						if ($result = $this -> Player -> login($form['login']['email'], $form['login']['password'])) {
							$this -> Session -> write('playerId', $result);
							if ($this -> Session -> read('fighterId'))
								$this -> Session -> delete('fighterId');
							$this -> redirect(array('controller' => 'Arenas', 'action' => 'sight'));
						} else $this -> set('error', "Mauvais couple email/mot de passe !");
					} else {
						if ($result = $this -> Player -> signin($form['login']['email'], $form['login']['password'])) {
							$this -> Session -> write('playerId', $result);
							if ($this -> Session -> read('fighterId'))
								$this -> Session -> delete('fighterId');
							$this -> redirect(array('controller' => 'Arenas', 'action' => 'fighter'));
						} else $this -> set('error', "Inscription ratée");
					}
					
				}
			}
			
			$fb = new Facebook\Facebook([
			  'app_id' => '941405372594453',
			  'app_secret' => 'b274c5d1644b161f11c4775fe494b02d',
			  'default_graph_version' => 'v2.4',
			]);

			$helper = $fb->getRedirectLoginHelper();

			$permissions = ['email']; // Optional permissions
			$loginUrl = $helper->getLoginUrl("http://webarena.com/WebArenaGroupSEA-01-12DG/Arenas/facebook", $permissions);

			$this -> set('loginUrl' , $loginUrl);
		} else $this -> redirect(array('controller' => 'Arenas', 'action' => 'index'));
	}
	
	public function facebook() {
		$fb = new Facebook\Facebook([
			  'app_id' => '941405372594453',
			  'app_secret' => 'b274c5d1644b161f11c4775fe494b02d',
			  'default_graph_version' => 'v2.4',
			]);
		  
		$helper = $fb->getRedirectLoginHelper();  
		  
		try {  
			$accessToken = $helper->getAccessToken(); 		  
		} catch(Facebook\Exceptions\FacebookResponseException $e) {  
		  // When Graph returns an error  
		  echo 'Graph returned an error: ' . $e->getMessage();  
		  exit;  
		} catch(Facebook\Exceptions\FacebookSDKException $e) {  
		  // When validation fails or other local issues  
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();  
		  exit;  
		}  

		if (isset($accessToken)) {
			try {
			  // Returns a `Facebook\FacebookResponse` object
			  $response = $fb->get('/me?fields=id,name,email', $accessToken);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  echo 'Graph returned an error: ' . $e->getMessage();
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  exit;
			}
			$user = $response->getGraphUser();
			
			if ($this -> Player -> existEmail($user['email'])) {
				if ($result = $this -> Player -> simpleLogin($user['email'])) {
					$this -> Session -> write('playerId', $result['id']);
					if ($this -> Session -> read('fighterId'))
						$this -> Session -> delete('fighterId');
					$this -> redirect(array('controller' => 'Arenas', 'action' => 'sight'));
				} else $this -> set('error', "Mauvais couple email/mot de passe !");
			} else {
				if ($result = $this -> Player -> signin($user['email'], '')) {
					$this -> Session -> write('playerId', $result);
					if ($this -> Session -> read('fighterId'))
						$this -> Session -> delete('fighterId');
					$this -> redirect(array('controller' => 'Arenas', 'action' => 'fighter'));
				} else $this -> set('error', "Inscription ratée");
			}
		}
	}
	
	public function sight() {
		$this -> set('title', 'Vue - Webarena');
		if ($this -> Session -> read('playerId')) {
			$playerId = $this -> Session -> read('playerId');
			if ($this -> Session -> read('fighterId'))
				$fighter = $this -> Fighter -> getFighter($this -> Session -> read('fighterId'));
			else $fighter = $this -> Fighter -> getFighterByPlayer($playerId);

			if ($fighter) {
				if ($this -> request -> data) {
					$form = $this -> request -> data;
					$i = 0;
					while (isset($form['levelUp' . $i])) {
						switch($form['levelUp' . $i]) {
						case 'strength':
							$fighter['skill_strength']++;
							break;
						case 'health': 
							$fighter['skill_health'] += 3;
							$fighter['current_health'] += 3;
							break;
						default:
							$fighter['skill_sight']++;
							break;
						}
						$fighter['xp']--;
						$i++;
					}
					$this -> Fighter -> save($fighter);
				}
				
				
				$fighterRealHealth = $fighter['current_health'] + ($this -> Tool -> getNumberOfTools(Arena::OBJET_BOUCLIER, $fighter['id']) * Arena::OBJET_BONUS);
				if ($fighterRealHealth > 0) {
					$tools = $this -> Tool -> find('all');
					$fighters = $this -> Fighter -> getRealFighters($tools);
					$surroundings = $this -> Surrounding -> find('all');
					
					$arena = new Arena();
					$arena -> setFighters($fighters);
					$arena -> setTools($tools);
					$arena -> setSurroundings($surroundings);
				
					if (isset($this -> request -> params['named']['moveToX']) && isset($this -> request -> params['named']['moveToY'])) {
						$moveToX = $this -> request -> params['named']['moveToX'];
						$moveToY = $this -> request -> params['named']['moveToY'];
						if ($arena -> deplacementPossible($fighter['coordinate_x'], $fighter['coordinate_y'], $moveToX, $moveToY)) {
							$dest = $arena -> at($moveToX, $moveToY);
							$moveOK = true;
							
							if ($dest != null) {
								switch ($dest) {
								case Arena::DECOR_PIEGE:
								case Arena::DECOR_MONSTRE:
									$this -> Event -> add($moveToX, $moveToY, $fighter['name'] . " est mort en allant vers X:" . $moveToX . "/Y:" . $moveToY);
									$this -> Fighter -> dead($fighter['id']);
									$moveOK = false;
									break;
								case Arena::OBJET_BOUCLIER:
								case Arena::OBJET_JUMELLES:
								case Arena::OBJET_POTION_MAGIQUE:
									$this -> Tool -> assign($fighter['id'], $moveToX, $moveToY);
									$this -> Event -> add($moveToX, $moveToY, $fighter['name'] . " a ramassé '" . $dest . "' à X:" . $moveToX . "/Y:" . $moveToY);
									break;
								}
							}
							
							if ($moveOK) {
								$fighter['coordinate_x'] = $moveToX;
								$this -> Event -> add($moveToX, $moveToY, $fighter['name'] . " s'est déplacé vers X:" . $moveToX . "/Y:" . $moveToY);
								$fighter['coordinate_y'] = $moveToY;
								$this -> Fighter -> save($fighter);
							}
						}
					}
					
					if (isset($this -> request -> params['named']['attackX']) && isset($this -> request -> params['named']['attackY'])) {
						$attackX = $this -> request -> params['named']['attackX'];
						$attackY = $this -> request -> params['named']['attackY'];
						$enemy = $this -> Fighter -> getFighterAt($attackX, $attackY);
						if ($enemy) {
							$fighterLevel = $this -> Fighter -> getLevel($fighter);
							$enemyLevel = $this -> Fighter -> getLevel($enemy);
							$realStrength = $fighter['skill_strength'] + ($this -> Tool -> getNumberOfTools(Arena::OBJET_POTION_MAGIQUE, $fighter['id']) * Arena::OBJET_BONUS);
							$realHealth = $enemy['current_health'] + ($this -> Tool -> getNumberOfTools(Arena::OBJET_BOUCLIER, $enemy['id']) * Arena::OBJET_BONUS);

							$this -> Event -> add($attackX, $attackY, $fighter['name'] . " attaque " . $enemy['name']);
							$seuil = 10 - $fighterLevel + $enemyLevel;
							$result = rand(0,20);
							if ($result >= $seuil) {
								$this -> Event -> add($attackX, $attackY, $fighter['name'] . " a réussi son attaque contre " . $enemy['name']);
								$fighter['level']++;
								$fighter['xp']++;
								$realHealth -= $realStrength;
								$enemy['current_health'] -= $realStrength;
								if ($realHealth <= 0) { //Ennemie KO
									$this -> Event -> add($attackX, $attackY, $enemy['name'] . " est mort sous les coups de " . $fighter['name']);
									$fighter['level'] += $enemyLevel;
									$fighter['xp'] += $enemyLevel;
								}
								$this -> Fighter -> save(array('Fighter' => $fighter));
								$this -> Fighter -> save(array('Fighter' => $enemy));
							} else $this -> Event -> add($attackX, $attackY, $fighter['name'] . " a raté son attaque contre " . $enemy['name']);
						} elseif ($arena -> isMonster($attackX, $attackY)) {
							$this -> Surrounding -> deleteMonster($attackX, $attackY);
							$this -> Event -> add($attackX, $attackY, $fighter['name'] . " a attaqué le monstre et l'a tué !");
							$fighter['coordinate_x'] = $attackX;
							$fighter['coordinate_y'] = $attackY;
							$this -> Fighter -> save($fighter);
						}
					}
					
					
					//Reconstruction finale avant affichage
					if ($this -> Session -> read('fighterId'))
						$fighter = $this -> Fighter -> getFighter($this -> Session -> read('fighterId'));
					else $fighter = $this -> Fighter -> getFighterByPlayer($playerId);
					$fighterRealHealth = $fighter['current_health'] + ($this -> Tool -> getNumberOfTools(Arena::OBJET_BOUCLIER, $fighter['id']) * Arena::OBJET_BONUS);
					if ($fighterRealHealth > 0) {
						$tools = $this -> Tool -> find('all');
						$fighters = $this -> Fighter -> getRealFighters($tools);
						$surroundings = $this -> Surrounding -> find('all');
						$arena = new Arena();
						$arena -> setFighters($fighters);
						$arena -> setTools($tools);
						$arena -> setSurroundings($surroundings);
						
						$nbJumelles = $this -> Tool -> find('count', array('conditions' => array('type = ' => Arena::OBJET_JUMELLES, 'fighter_id = ' => $fighter['id'])));
						$realSight = $fighter['skill_sight'] + Arena::OBJET_BONUS * $nbJumelles;

						$this -> set('arenaSize', array(Arena::SIZE_X, Arena::SIZE_Y));
						$this -> set('arena', $arena -> draw($fighter['coordinate_x'], $fighter['coordinate_y'], $realSight));
						$this -> set('events', $this -> Event -> currentEvents);
						$this -> set('fighter', $fighter);
					
						if ($fighter['xp'] >= 4) {
							$this -> set('levelUp', floor($fighter['xp'] / 4));
						}
					}  else $this -> set('error', "Le combattant s'est fait tué !");
				} else $this -> set('error', "Le combattant s'est fait tué !");
			} else $this -> set('error', 'Erreur : Fighter introuvable');
		} else $this -> redirect(array('controller' => 'Arenas', 'action' => 'login'));
	}
	
	public function fighter() {
		$this -> set('title', 'Combattant - Webarena');
		if ($this -> Session -> read('playerId')) {				
			$playerId = $this -> Session -> read('playerId');
			if (isset($this -> request -> data['fighter']) && strlen($this -> request -> data['fighter']['name']) > 0) {
				$form = $this -> request -> data['fighter'];
				$tools = $this -> Tool -> find('all');
				$fighters = $this -> Fighter -> getRealFighters($tools);
				$surroundings = $this -> Surrounding -> find('all');
				$arena = new Arena();
				$arena -> setFighters($fighters);
				$arena -> setTools($tools);
				$arena -> setSurroundings($surroundings);
				$coor = $arena -> getValidFighterPosition();
				$createFighter = array('Fighter' => array('name' => $form['name'], 'level' => 4, 'xp' => 0, 'coordinate_x' => $coor['coordinate_x'], 'coordinate_y' => $coor['coordinate_y'], 'skill_sight' => 1, 'skill_strength' => 1, 'skill_health' => 3, 'current_health' => 3, 'player_id' => $playerId));
				$fighterCreation = $this -> Fighter -> save($createFighter);
				if ($fighterCreation) {
					$fighter = $this -> Fighter -> getFighterAt($coor['coordinate_x'], $coor['coordinate_y']);
					$this -> Session -> write('fighterId', $fighter['id']);
					$this -> Event -> add($coor['coordinate_x'], $coor['coordinate_y'], $fighter['name'] . " a rejoint l'arnène à la position X : " . $coor['coordinate_x'] . " | Y : " . $coor['coordinate_y']);
				}
			} 
			
			if (isset($this -> request -> params['named']['fighter'])) {
				$fighter = $this -> Fighter -> getFighter($this -> request -> params['named']['fighter']);
				$this -> Session -> write('fighterId', $fighter['id']);
			} else {
				if ($this -> Session -> read('fighterId'))
					$fighter = $this -> Fighter -> getFighter($this -> Session -> read('fighterId'));
				else $fighter = $this -> Fighter -> getFighterByPlayer($playerId);
			}
			
			//Utilisation d'une classe externe d'upload de fichiers (que j'ai développé)
			if (isset($this -> params['form']['avatar'])) {
				$file = $this -> params['form']['avatar'];
				$fu = new FileUploader('img/avatars/', "3Mo");
				$fu -> add($file, $fighter['id'] . "." . FileUploader::extractExtension($file['name']), array(FileUploader::JPG, FileUploader::PNG, FileUploader::GIF));
				$result = $fu -> resize(128, 128, $fighter['id']);
			}

			if ($fighter) {
				$this -> set('fighter', $fighter);
				$this -> set('fighters', $this -> Fighter -> getFightersOf($playerId));
			}
		} else $this -> redirect(array('controller' => 'Arenas', 'action' => 'login'));
	}
	
	public function diary() {
		$this -> set('title', 'Journal - Webarena');
		if ($this -> Session -> read('playerId')) {
			$playerId = $this -> Session -> read('playerId');
			if ($this -> Session -> read('fighterId'))
				$fighter = $this -> Fighter -> getFighter($this -> Session -> read('fighterId'));
			else $fighter = $this -> Fighter -> getFighterByPlayer($playerId);
			
			$fighterRealHealth = $fighter['current_health'] + ($this -> Tool -> getNumberOfTools(Arena::OBJET_BOUCLIER, $fighter['id']) * Arena::OBJET_BONUS);
			if ($fighterRealHealth > 0) {
			
				$tools = $this -> Tool -> find('all');
				$fighters = $this -> Fighter -> getRealFighters($tools);
				$surroundings = $this -> Surrounding -> find('all');
				$arena = new Arena();
				$arena -> setFighters($fighters);
				$arena -> setTools($tools);
				$arena -> setSurroundings($surroundings);
				
				$nbJumelles = $this -> Tool -> find('count', array('conditions' => array('fighter_id = ' => $fighter['id'])));
				$realSight = $fighter['skill_sight'] + Arena::OBJET_BONUS * $nbJumelles;
				$visible = $arena -> visibleByFighter($realSight, $fighter['coordinate_x'], $fighter['coordinate_y']);
				$events = $this -> Event -> recup_event_24($visible);
				$this -> set('events',$events);
			} else $this -> redirect(array('controller' => 'Arenas', 'action' => 'fighter'));
		} else $this -> redirect(array('controller' => 'Arenas', 'action' => 'login'));
		
	}
}
?>