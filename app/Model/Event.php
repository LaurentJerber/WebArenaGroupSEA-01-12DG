<?php

App::uses('AppModel', 'Model');

class Event extends AppModel {
	
    public $displayField = 'name';

    public $belongsTo = array(  );
	public $currentEvents = array();
   
	public function recup_event_24($visible){
		//2014-11-07 12:00:00
		$ilya24h = date("Y-m-d H:i:s", time() - (60*60*24));
		$allEvents = $this -> query("SELECT * FROM events WHERE date >= '" . $ilya24h ."'");
		$events = array();
		$sVisible = array();
		foreach ($visible as $v) {
			$sVisible[] = $v['coordinate_x'] . '|' . $v['coordinate_y'];
		}
		
		foreach ($allEvents as $event) {
			if (in_array($event['events']['coordinate_x'] . '|' . $event['events']['coordinate_y'], $sVisible))
				$events[] = $event;
		}
		return $events;
	}   
   
	public function add($x, $y, $message) {
		$now = date("Y-m-d H:i:s", time());
		$event = array('Event' => array('name' => $message, 'date' => $now,  'coordinate_x' => $x, 'coordinate_y' => $y));
		$this -> save($event);
		$this -> currentEvents[] = $message;
	}
   

}