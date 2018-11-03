<?php

for($i = 1 ; !$unlocked and ($i <= 4) ; $i++) {
	if($player->getOrb($i)) $unlocked = true;
}

?>