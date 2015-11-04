<?php

App::uses('AppModel', 'Model');

class Event extends AppModel {
	
    public $displayField = 'name';

    public $belongsTo = array(  );
   
	public function recup_event_24(){
		//2014-11-07 12:00:00
		$ilya24h = date("Y-m-d H:i:s", time() - (60*60*24));
		$events = $this -> query("SELECT * FROM events WHERE date >= '" . $ilya24h ."'");
		
		return $events;
	}   
   
   
   

}