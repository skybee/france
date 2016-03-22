<?php

// If you're using PHP < 5.5 you can use this code to add the same functionality. 
// If you pass the wrong $mode in it will silently fail. 
// You might want to add your own error handling for this case.

if (!function_exists('imageflip')) {
  define('IMG_FLIP_HORIZONTAL', 0);
  define('IMG_FLIP_VERTICAL', 1);
  define('IMG_FLIP_BOTH', 2);

  function imageflip($image, $mode) {
    switch ($mode) {
      case IMG_FLIP_HORIZONTAL: {
        $max_x = imagesx($image) - 1;
        $half_x = $max_x / 2;
        $sy = imagesy($image);
        $temp_image = imageistruecolor($image)? imagecreatetruecolor(1, $sy): imagecreate(1, $sy);
        for ($x = 0; $x < $half_x; ++$x) {
          imagecopy($temp_image, $image, 0, 0, $x, 0, 1, $sy);
          imagecopy($image, $image, $x, 0, $max_x - $x, 0, 1, $sy);
          imagecopy($image, $temp_image, $max_x - $x, 0, 0, 0, 1, $sy);
        }
        break;
      }
      case IMG_FLIP_VERTICAL: {
        $sx = imagesx($image);
        $max_y = imagesy($image) - 1;
        $half_y = $max_y / 2;
        $temp_image = imageistruecolor($image)? imagecreatetruecolor($sx, 1): imagecreate($sx, 1);
        for ($y = 0; $y < $half_y; ++$y) {
          imagecopy($temp_image, $image, 0, 0, 0, $y, $sx, 1);
          imagecopy($image, $image, 0, $y, 0, $max_y - $y, $sx, 1);
          imagecopy($image, $temp_image, 0, $max_y - $y, 0, 0, $sx, 1);
        }
        break;
      }
      case IMG_FLIP_BOTH: {
        $sx = imagesx($image);
        $sy = imagesy($image);
        $temp_image = imagerotate($image, 180, 0);
        imagecopy($image, $temp_image, 0, 0, 0, 0, $sx, $sy);
        break;
      }
      default: {
        return;
      }
    }
    imagedestroy($temp_image);
  }
}