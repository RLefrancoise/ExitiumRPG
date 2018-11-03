<?php

	include_once(__DIR__ . "/rpgconfig.php");
	include_once(__DIR__ . "/ItemStorage.class.php");
	
	class Warehouse extends ItemStorage{
		
		public function __construct($item_list, $items_number) {
			parent::__construct(WAREHOUSE_SIZE, $item_list, $items_number);
		}
	}
?>