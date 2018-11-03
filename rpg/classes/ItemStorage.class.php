<?php

include_once(__DIR__ . "/Item.class.php");
	
class ItemPair {
	public $item;
	public $number;
	
	public function __construct($item, $number) {
		$this->item = $item;
		$this->number = $number;
	}
}
	
class ItemStorage {

	protected $items;
	protected $size;
	
	public function __construct($size, $item_list, $items_number) {
		$this->size = (int) $size;
		
		for($i = 0 ; $i < $this->size ; $i++){
			if($item_list[$i] == null) continue;
			$this->items[$i] = new ItemPair($item_list[$i], $items_number[$i]);
		}
	}
	
	/* Add an item in the storage. If the item is limited to one per
	* slot, it is added in an empty space. If not, it is added amongst
	* the others.
	*
	* Return true if item is added in the storage, false otherwise.
	*/
	public function addItem(Item $item) {
		
		//look for the type of item (armor part, orb, syringe)
		$item_type = $this->getTypeOfItem($item);
		if($item_type == '') return false;
		
		// this item is limited to one per slot, so we add it in the first free slot
		if($item->isOnePerSlot()) {
			if($this->isFull()) {
				return false;
			}
			
			$this->items[$this->getNextFreeSlot()] = new ItemPair($item, 1);
		}
		// this item is allowed to be multiple times in the same slot, so we check if at least one examplary exists
		else {
			$item_found = false;
			for($i = 0 ; $i < $this->size ; $i++) {
				if($this->items[$i]->item == null) continue;
				if( ($item->getId() == $this->items[$i]->item->getId()) and ($item_type == $this->getTypeOfItem($this->items[$i]->item)) ){
					$item_found = true;
					break;
				}
			}
			// if an examplary is found, we just add one to its quantity
			if($item_found) {
				//$this->items_number[$i] += 1;
				$this->items[$i]->number += 1;
			}
			// else we add it in the next available slot
			else {
				//$this->items[$this->getNextFreeSlot()] = $item;
				//$this->items_number[$this->getNextFreeSlot()] = 1;
				$this->items[$this->getNextFreeSlot()] = new ItemPair($item, 1);
			}
		}
		
		return true;
	}

	/* Remove an item in the inventory.
	*
	*/
	public function removeItem($index) {
		if($index < 0 or $index >= $this->size)
			return false;
		
		//get the current size(needed for array_chunk)
		//$size = count($this->items);
		//delete item
		unset($this->items[$index]);
		//update indexes
		//$tmp = array_chunk($this->items, $size);
		//$this->items = $tmp[0];
		
		return true;
	}

	public function getItem($index) {
		if($index < 0 or $index >= $this->size)
			return false;
			
		return $this->items[$index]->item;
	}

	public function hasItem(Item $item) {
		$item_found = false;
		for($i = 0 ; $i < $this->size ; $i++) {
			if($this->items[$i]->item == null) continue;
			if( ($item->getId() == $this->items[$i]->item->getId()) and ($this->getTypeOfItem($item) == $this->getTypeOfItem($this->items[$i]->item)) ){
				$item_found = true;
				break;
			}
		}
		
		return $item_found;
	}

	public function getQuantityOfItem($index) {
		if($index < 0 or $index >= $this->size)
			return 0;
			
		return intval($this->items[$index]->number);
	}

	public function getNumberOfItems() {
		return count($this->items);
	}

	public function getNextFreeSlot() {
		if($this->isFull()) return -1;
		
		for($i = 0 ; $i < $this->size ; $i++) {
			if($this->items[$i] == null) return ($i+1);
		}
		
		return -1;
	}

	public function isFull() {
		return ($this->getNumberOfItems() >= $this->size);
	}

	public function setItem(Item $i, $number, $index) {
		if($index < 0 or $index >= $this->size) return false;
		
		if(!isset($this->items[$index])) {
			$this->items[$index] = new ItemPair($i, 1);
		}
		
		$this->items[$index]->item = $i;
		if($number >= 1)
			$this->items[$index]->number = $number;
			//$this->items_number[$index] = $number;
		else
			$this->items[$index]->number = 1;
			//$this->items_number[$index] = 1;
	}

	public function getTypeOfItem(Item $i) {
		$item_type = '';
		switch(get_class($i)) {
			case 'SetPart':
				switch($i->getType()){
					case ARMOR_CLOTH:
						$item_type = 'cloth';
						break;
					case ARMOR_LEGGINGS:
						$item_type = 'leggings';
						break;
					case ARMOR_GLOVES:
						$item_type = 'glove';
						break;
					case ARMOR_SHOES:
						$item_type = 'shoe';
						break;
					default:
						break;
				}
				break;
				
			case 'Orb':
				$item_type = 'orb';
				break;
				
			case 'Syringe':
				$item_type = 'syringe';
				break;
			case 'Special':
				$item_type = 'special';
				break;
			case 'Ralz':
				$item_type = 'ralz';
				break;
		}
		
		return $item_type;
	}
}

?>
