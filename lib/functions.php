<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/10/2016
 * Time: 8:28 PM
 */

//return a string of random text of a defined length
function random_text($count, $rm_similar=false)
{
    //create list of charactors
    $chars = array_flip(array_merge(rang(0,9), rang('A', 'Z')));

    //remove similar looking  characters that might cause confusion
    if($rm_similar)
    {
        unset($chars[0],$chars[1],$chars[2],$chars[5],$chars[8],$chars['B'],
            $chars['I'],$chars['O'],$chars['Q'],$chars['S'],$chars['U'],$chars['V'],$chars['Z']);
    }

    //generate random text
    for($i = 0, $text=''; $i < $count; $i++)
    {
        $text.=array_rand($chars);
    }

    return $text;
}