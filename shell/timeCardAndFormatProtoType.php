<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 11/11/15
 * Time: 5:32 PM
 */

/**
 * testing an associative array for the loading of time_card_format and time_card data.
 */
$myArray = [];

$myArray['SUN'] = 0.0;
$myArray['MON'] = 0.0;
$myArray['TUE'] = 5.0;
$myArray['WED'] = 0.0;
$myArray['THU'] = 3.0;
$myArray['FRI'] = 2.0;
$myArray['SAT'] = 10.0;

$i=0;
foreach ($myArray as $day => $key) {
    print("Day $i is $day with a value of $key \n");
    $i++;
}

//for ($i=0; $i<sizeof($myArray); $i++) {
//    print("Day $i is $day with a value of $myArray[$i] \n");
//}