<?php

class ItemPackElement {
	public $item;
	public $number;
	
	public function __construct($item, $number) {
		$this->item = $item;
		$this->number = $number;
	}
}

class ItemPack {

	private $id;
	private $name;
	private $desc;
	private $items;
	private $xp;
	private $ralz;
	
	public function __construct($data) {
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->desc = $data['descr'];
		$this->items = $data['items'];
		$this->xp = $data['xp'];
		$this->ralz = $data['ralz'];
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDesc() {
		return $this->desc;
	}
	
	public function getItems() {
		return $this->items;
	}
	
	public function getXp() {
		return $this->xp;
	}
	
	public function getRalz() {
		return $ralz;
	}
}

?>