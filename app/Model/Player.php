<?php

App::uses('AppModel', 'Model');

class Player extends AppModel {
	
    public $displayField = 'name';

    public $belongsTo = array(
   );
   
   public function simpleLogin($email) {
	   $player = $this -> getByEmail($email);
		if ($player) {
			return $player;
		} return false;
   }
   
	public function login($email, $password) {
		$player = $this -> simpleLogin($email);
		if ($player) {
			$crypted = md5($password);
			if ($crypted == $player['password'])
				return $player['id'];
		}
		return false;
	}
	
	public function signin($email, $password) {
		$player = array('Player' => array('email' => $email, 'password' => md5($password)));
		$this -> save($player);
		$player = $this -> getByEmail($email);
		if ($player)
			return $player['id'];
		return false;
	}
	
	public function getByEmail($email) {
		$query = $this -> find('first', array('conditions' => array('email = ' => $email)));
		if (isset($query)) {
			return $query['Player'];
		} return null;
	}
	
	public function getByID($id) {
		$query = $this -> find('all', array('conditions' => array('id = ' => $id)));
		if (isset($query[0])) {
			return $query[0]['Player'];
		} return null;
	}
	
	public function existEmail($email) {
		$player = $this -> getByEmail($email);
		if ($player)
			return true;
		return false;
	}
	
	public function existID($id) {
		$player = $this -> getByID($id);
		if ($player)
			return true;
		return false;
	}
	
	public function fbExist($response) {
		if (isset($response['id'])) {
			$player = $this -> find('all', array('conditions' => array('id = ' => $response['id'])));
			if ($player) return true;
		} return false;
	}
}