<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/11/2016
 * Time: 8:56 PM
 */

include '../../llib/functions.php';

if(!isset($_SESSION))
{
    session_start();
    header('Cache-control:private');
}

//create a 65x20 pixel image
$width = 65;
$height  = 20;
$image = imagecreate(65, 20);

//fill the image background color
$bg_color = imagecolorallocate($image, 0x33, 0x66, 0xFF);
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

$text = random_text(5);

//centering
$font = 5;
$x = imagesx($image) /2 - strlen($text) * imagefontwidth($font) / 2;
$y = imagesy($image) /2 - imagefontwidth($font) / 2;

//write text on image
$fg_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
imagestring($image, $font, $x, $y, $text, $fg_color);

//save the CAPTCHA string for later comparison
$_SESSION['captcha'] = $text;

//output the image
header('Content-type: image/png');
imagepng($image);

imagedestroy($image);

