<?php 

App::uses('AppController', 'Controller');

/**
 * Main controller of our small application
 *
 * @author ...
 */
class ArenasController extends AppController
{
	public $uses = array('Player', 'Fighter', 'Event');
    /**
     * index method : first page
     *
     * @return void
     */
    public function index()
    {
		$this -> set('myname', "Laurent Jerber");
    }
	
	public function login() {
	
	}
	
	public function fighter() {
		
		$this -> set('avantMove', $this -> Fighter -> getCoordinates(1));
		$this -> Fighter -> doMove(1, "north");
		$this -> set('apresMove', $this -> Fighter -> getCoordinates(1));
	}
	
	public function sight() {
		$fighterX = $this -> Fighter -> getX(1);
		$fighterY = $this -> Fighter -> getY(1);
		$movable = array(
			($fighterX + 1) . "|" . $fighterY,
			($fighterX - 1) . "|" . $fighterY,
			$fighterX . "|" . ($fighterY + 1),
			$fighterX . "|" . ($fighterY - 1)
		);
		$this -> set('current_x', $fighterX);
		$this -> set('current_y', $fighterY);
		$this -> set('movable', $movable);
		 
		 
		if ($this->request->is('post')) {
			pr($this->request->data);
		}

	}
	
	public function diary() {
		$this -> set('raw',$this->Event->find());
	}
}
?>