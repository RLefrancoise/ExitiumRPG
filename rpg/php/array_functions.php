<?php

function shuffle_assoc($array)
{
    // Initialize
        $shuffled_array = array();


    // Get array's keys and shuffle them.
        $shuffled_keys = array_keys($array);
        shuffle($shuffled_keys);


    // Create same array, but in shuffled order.
        foreach ( $shuffled_keys AS $shuffled_key ) {

            $shuffled_array[  $shuffled_key  ] = $array[  $shuffled_key  ];

        } // foreach


    // Return
        return $shuffled_array;
}

?>
