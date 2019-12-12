<?php

function secure($field) {
    $field = trim($field);
    $field = stripslashes($field);
    $field = htmlspecialchars($field);
    return $field;
}

Function addTimeToImages($image_name)
{
    $image_name_array = explode('.' , $image_name);

    $image_name_array [0] =  $image_name_array [0] .'_'.time();

    $image_name =  $image_name_array [0] .'.'. $image_name_array [1];

    Return $image_name ;

}