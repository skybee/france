<?php


function getNoImageUrl(){

    $noImageDir = '/img/no_img/flip/';


    $imgNames = array(
        'no_img_340x220-1.jpg',
        'no_img_340x220-2.jpg',
        'no_img_340x220-3.jpg',
        'no_img_340x220-4.jpg',
        'no_img_340x220-5.jpg',
        'no_img_340x220-6.jpg',
        'no_img_340x220-7.jpg',
        'no_img_340x220-8.jpg',
        'no_img_340x220-9.jpg'
        );



    $rndImgName = $imgNames[mt_rand(0,8)];

    
    return $noImageDir.$rndImgName;
    
}

function getNoImageUrlContent(){
    return '/img/no_img/content/no_img_content_flip.jpg';
}

//    header("HTTP/1.1 301 Moved Permanently"); 
//    header("Location: ".$noImageDir.$rndImgName); 
//    exit($_SERVER['REQUEST_URI']);