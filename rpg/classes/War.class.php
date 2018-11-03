<?php

include_once(__DIR__ . "/rpgconfig.php");

class War {

	private $id;
	private $topic_id;
	
	private $orga_points;
	
	/*private $empire_points;
	private $revo_points;
	private $eclypse_points;
	private $conseil_points;*/
	
	public __construct($data) {
		$this->id = $data['id'];
		$this->topic_id = $data['topic_id'];
		/*$this->empire_points = $data['empire_points'];
		$this->revo_points = $data['revo_points'];
		$this->eclypse_points = $data['eclypse_points'];
		$this->conseil_points = $data['conseil_points'];*/
		
		$this->orga_points = array();
		
		$this->orga_points[ORGA_EMPIRE] 	= 	$data['empire_points'];
		$this->orga_points[ORGA_REVO]		= 	$data['revo_points'];
		$this->orga_points[ORGA_ECLYPSE]	=	$data['eclypse_points'];
		$this->orga_points[ORGA_CONSEIL]	=	$data['conseil_points'];
	}
	
	public getId() {
		return $this->id;
	}
	
	public getTopicId() {
		return $this->topic_id;
	}
	
	public getPoints($orga) {
		return $this->orga_points[$orga];
	}
	
	public setPoints($orga, $points) {
		$this->orga_points[$orga] = (int) $points;
	}
	
	public isOver() {
		foreach($this->orga_points as $orga => $points) {
			if($points <= 0) return true;
		}
		
		return false;
	}
	
	/*public getEmpirePoints() {
		return $this->empire_points;
	}
	
	public getRevoPoints() {
		return $this->revo_points;
	}
	
	public getEclypsePoints() {
		return $this->eclypse_points;
	}
	
	public getConseilPoints() {
		return $this->conseil_points;
	}
	
	public setEmpirePoints($points) {
		$this->empire_points = $points;
	}
	
	public setRevoPoints($points) {
		$this->revo_points = $points;
	}
	
	public setEclypsePoints($points) {
		$this->eclypse_points = $points;
	}
	
	public setConseilPoints($points) {
		$this->conseil_points = $points;
	}*/
}

?>