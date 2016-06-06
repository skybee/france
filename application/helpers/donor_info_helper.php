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
        'Le Parisien'           => array('host'=>'www.leparisien.fr'),
        'spot-on-news.de'       => array('host'=>'spot-on-news.de'),
        'BANG Showbiz'          => array('host'=>'www.bangshowbiz.com'),
        'dpa'                   => array('host'=>'www.dpa.de'),
        'ITespresso.de'         => array('host'=>'www.itespresso.de'),
        'News-Insider.de'       => array('host'=>'www.news-insider.de'),
        'dieKLEINERT.de'        => array('host'=>'www.kleinert.de'),
        'Filmstarts'            => array('host'=>'www.filmstarts.de'),
        'APA'                   => array('host'=>'www.apa.at'),
        'AEG'                   => array('host'=>'www.aeg.de'),
        'The Verge'             => array('host'=>'www.theverge.com'),
        'Irish Independent'     => array('host'=>'www.independent.ie'),
        'PTI'                   => array('host'=>'www.ptinews.com'),
        'News18'                => array('host'=>'www.news18.com'),
        'Press Association'     => array('host'=>'www.pressassociation.com'),
        'The New York Times'    => array('host'=>'www.nytimes.com'),
        'Firstpost'             => array('host'=>'www.firstpost.com'),
        'Omnisport'             => array('host'=>'omnisport.performgroup.com'),
        'The Atlantic'          => array('host'=>'www.theatlantic.com'),
        'Autocar'               => array('host'=>'www.autocar.co.uk'),
        'Read Cars'             => array('host'=>'readcars.co'),
        'Bloomberg'             => array('host'=>'www.bloomberg.com'),
        'Reuters'               => array('host'=>'www.reuters.com'),
        'The Financial Times'   => array('host'=>'www.ft.com'),
        'House Beautiful'       => array('host'=>'www.housebeautiful.com'),
        'Wanderlust'            => array('host'=>'www.wanderlust.co.uk'),
        'Refinery29'            => array('host'=>'www.refinery29.com'),
        'Auto123.com'           => array('host'=>'www.auto123.com'),
        'The Indian Express'    => array('host'=>'indianexpress.com'),
        'Woman\'s Day'          => array('host'=>'www.womansday.com.au'),
        'IBN Live'              => array('host'=>'www.news18.com'),
        'AOL'                   => array('host'=>'www.aol.com'),
        'The Huffington Post'   => array('host'=>'www.huffingtonpost.com'),
        'Microsoft Store'       => array('host'=>'www.microsoftstore.com'),
        'Windows Store | Sponsored' => array('host'=>'www.microsoftstore.com'),
        'Associated Press'      => array('host'=>'www.ap.org')
        
    );
    
    if( isset($donor_name[$name]) == false )
    {
        return false;
    }
    
    return $donor_name[$name];
}

