<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function get_donor_info_by_name($name){ //получение информации о доноре по имени, если отсутствует ссылка
    
    $donor_name = array(
        'ТАСС'                  => array('host'=>'tass.ru'),
        'Русская служба BBC'    => array('host'=>'www.bbc.com'),
        'AFP'                   => array('host'=>'www.afp.com'),
        'Relaxnews (AFP)'       => array('host'=>'www.afprelaxnews.com'),
        'Business Insider'      => array('host'=>'www.businessinsider.com'),
        'Europe 1'              => array('host'=>'www.europe1.fr'),
        'Silicon.fr'            => array('host'=>'www.silicon.fr'),
        'Le Figaro'             => array('host'=>'www.lefigaro.fr'),
        'Le Parisien'           => array('host'=>'www.leparisien.fr')
    );
    
    if( isset($donor_name[$name]) == false )
    {
        return false;
    }
    
    return $donor_name[$name];
}

