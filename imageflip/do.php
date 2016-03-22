<?php
include './imageflip_php_5.5.php'; 

// File
$filename = '..'.$_SERVER['REQUEST_URI'];

//$log = file_get_contents('./ua.log');
//$log .= "\n".date("Y.m.d H:i:s").' '.$_SERVER['REMOTE_ADDR'].' '.$_SERVER['HTTP_USER_AGENT'];
//file_put_contents('./ua.log', $log);


preg_match("#\.([a-z]{3,4})$#", $filename, $arr);

if(isset($arr[1]) && !empty($arr[1]))
{
    switch ($arr[1])
    {
        case 'png':
            $header = 'image/png';
            $createImgFunc = '$im = imagecreatefrompng($filename);';
            $outputImgFunc = 'imagepng($im);';
            break;
        case 'jpg':
            $header = 'image/jpeg';
            $createImgFunc = '$im = imagecreatefromjpeg($filename);';
            $outputImgFunc = 'imagejpeg($im);';
            break;
        case 'jpeg':
            $header = 'image/jpeg';
            $createImgFunc = '$im = imagecreatefromjpeg($filename);';
            $outputImgFunc = 'imagejpeg($im);';
            break;
        default :
            $header = false;
    }
}

if(!$header)
{
    echo file_get_contents($filename);
    exit();
}

// Content type
header('Content-type: '.$header);

// Load
eval($createImgFunc);

// Flip it horizontally
imageflip($im, IMG_FLIP_HORIZONTAL);

// Output
eval($outputImgFunc);
imagedestroy($im);

