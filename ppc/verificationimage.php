<?php

/**
 * ppc/verificationimage.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: verificationimage
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
// ----------------------------------------- 
//  The Web Help .com
// test by azie 28/04/2014
// ----------------------------------------- 

header('Content-type: image/jpeg');

$width = 50;
$height = 24;

$my_image = imagecreatetruecolor($width, $height);

imagefill($my_image, 0, 0, 0xFFFFFF);

// add noise
for ($c = 0; $c < 40; $c++){
	$x = rand(0,$width-1);
	$y = rand(0,$height-1);
	imagesetpixel($my_image, $x, $y, 0x000000);
	}

$x = rand(1,10);
$y = rand(1,10);

$rand_string = rand(1000,9999);
imagestring($my_image, 5, $x, $y, $rand_string, 0x000000);

setcookie('tntcon',(md5($rand_string).'a4xn'));

imagejpeg($my_image);
imagedestroy($my_image);
?>