<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function get_donor_info_by_name($name){ //получение информации о доноре по имени, если отсутствует ссылка
    
    $donor_name = array(
        'ТАСС'                  => array('host'=>'tass.ru'),
        'Русская служба BBC'    => array('host'=>'www.bbc.com')
    );
    
    if( isset($donor_name[$name]) == false )
    {
        return false;
    }
    
    return $donor_name[$name];
}

