<?php
$output = "";
$counter = 1;

while($counter <= 100) {
    if ($counter % 3 == 0) {
        $output .= "foo";
        if ($counter % 5 == 0) {
            $output .= "bar, ";
        } else {
            $output .= ", ";
        }
    } elseif ($counter % 5 == 0) {
        $output .= "bar, ";
    } else {
        $output .= $counter;
        $output .= ", ";
    }
    $counter++;
}

echo $output."\n";