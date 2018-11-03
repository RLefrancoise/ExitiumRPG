<?php

/*
* Return float number between 0 and 1.
*/
function rand_float() {
	return (float)rand()/(float)getrandmax();
}

?>