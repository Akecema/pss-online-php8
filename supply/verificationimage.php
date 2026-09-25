<?php

/**
 * supply/verificationimage.php
 * Part of: Supply Chain module
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

$rand_string = random_int(1000, 9999);
imagestring($my_image, 5, $x, $y, $rand_string, 0x000000);

// The answer stays on the server. It used to go out as md5(answer) in a cookie, and with only 9000
// possible answers anyone could read their own cookie and reverse it instantly.
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$_SESSION['tntcon'] = (string) $rand_string;

imagejpeg($my_image);
imagedestroy($my_image);
?>