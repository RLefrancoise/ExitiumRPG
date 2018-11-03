<?php

for($i = 1 ; !$unlocked and ($i <= 4) ; $i++) {
	$orb = $player->getOrb($i);
	if($orb) {
		//orb is cursed ?
		switch($orb->getId()) {
			//equity
			case 22:
				$unlocked = true;
		}
	}
}

?>