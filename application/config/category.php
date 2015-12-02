<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');




$config['category']['default']['cache_time']['main_page']           = 10; //minutes
$config['category']['default']['cache_time']['top_slider']          = 15;
//$config['category']['default']['cache_time']['right_top_news']      = 15;
$config['category']['default']['cache_time']['right_last_news']     = 5;
$config['category']['default']['like_news_day']                     = 10; // +/- cnt day
$config['category']['default']['like_news_cache']                   = 1; //  hour
$config['category']['default']['like_news_cache_for_old']           = 24*60; //  hour /время кеша для старой новости
$config['category']['default']['top_news_time']                     = 10; // hour
$config['category']['default']['right_top_news_time']               = 24; // hour

// <news>
$config['category']['news']                                         = $config['category']['default'];

$config['category']['news']['health']                               = $config['category']['news'];
$config['category']['news']['health']['cache_time']['top_slider']   = 60;
$config['category']['news']['health']['like_news_day']              = 30; // +/- cnt day
$config['category']['news']['health']['like_news_cache']            = 24*5; //  hour
$config['category']['news']['health']['top_news_time']              = 24*10; // hour

$config['category']['news']['science']                              = $config['category']['news']['health'];
// <news>


// <hi-tech>
$config['category']['hi-tech']['cache_time']['main_page']           = 10; //minutes
$config['category']['hi-tech']['cache_time']['top_slider']          = 60;
//$config['category']['hi-tech']['cache_time']['right_top_news']      = 60;
$config['category']['hi-tech']['cache_time']['right_last_news']     = 30;
$config['category']['hi-tech']['like_news_day']                     = 180; // +/- cnt day
$config['category']['hi-tech']['like_news_cache']                   = 24*5; //  hour
$config['category']['hi-tech']['top_news_time']                     = 24*30; // hour
$config['category']['hi-tech']['right_top_news_time']               = 24*10; // hour
// </hi-tech>

// <women>
$config['category']['women']['cache_time']['main_page']             = 10; //minutes
$config['category']['women']['cache_time']['top_slider']            = 60;
//$config['category']['women']['cache_time']['right_top_news']      = 60;
$config['category']['women']['cache_time']['right_last_news']       = 60;
$config['category']['women']['like_news_day']                       = 730; // +/- cnt day
$config['category']['women']['like_news_cache']                     = 24*30; //  hour
$config['category']['women']['top_news_time']                       = 24*730; // hour
$config['category']['women']['right_top_news_time']                 = 24*730; // hour
// </women>

