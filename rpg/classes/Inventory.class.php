<?php

	include_once(__DIR__ . "/rpgconfig.php");
	include_once(__DIR__ . "/ItemStorage.class.php");
	
	class Inventory extends ItemStorage{
		
		public function __construct($item_list, $items_number) {
			parent::__construct(INVENTORY_SIZE, $item_list, $items_number);
		}
	}
?>