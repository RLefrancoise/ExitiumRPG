<?php

include_once("../classes/Inventory.class.php");

$inv = new Inventory();
$inv->addItem(new Item("Item 1"));
print_r($inv);
$inv->addItem(new Item("Item 2"));
print_r($inv);
$inv->addItem(new Item("Item 3"));
print_r($inv);
$inv->removeItem(1);
print_r($inv);

?>