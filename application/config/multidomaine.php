<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


define('STATIC_REMOTE_HOST', 'us.static.lalalay.com');
define('STATIC_REMOTE_ADD_URI', '/file_remote/add_file/');
define('STATIC_REMOTE_DEL_URI', '/file_remote/del_file/');

$config_var['pressfrom.com']['mail']                    = 'mail@pressfrom.com';
$config_var['pressfrom.com']['logo_img']                = 'logo-pressfrom-1.png';
$config_var['pressfrom.com']['logo_img_mobile']         = 'logo-fr-mobile.png';
$config_var['pressfrom.com']['site_name']               = 'PressFrom';

$config_var['francais-express.com']['mail']             = 'mail@francais-express.com';
$config_var['francais-express.com']['logo_img']         = 'logo-fr.jpg';
$config_var['francais-express.com']['logo_img_mobile']  = 'logo-fr-mobile.png';
$config_var['francais-express.com']['site_name']        = 'Francais Express';

$config_var['lalalay.com']['mail']                      = 'mail@lalalay.com';
$config_var['lalalay.com']['logo_img']                  = 'logo-pressfrom-1.png';
$config_var['lalalay.com']['logo_img_mobile']           = 'logo-fr-mobile.png';
$config_var['lalalay.com']['site_name']                 = 'LaLaLay';

$config_var['smiexpress.ru']['mail']                    = 'mail@smiexpress.ru';
$config_var['smiexpress.ru']['logo_img']                = 'logo-ru.jpg';
$config_var['smiexpress.ru']['logo_img_mobile']         = 'logo-ru-mobile.png';
$config_var['smiexpress.ru']['site_name']               = 'СМИ Express';

$config_var['default'] = $config_var['pressfrom.com'];


$lock_host = array(
    'telegraph.co.uk',
    'theguardian.com', 
    'independent.co.uk', 
    'standard.co.uk', 
    'mirror.co.uk', 
    'birminghammail.co.uk', 
    'liverpoolecho.co.uk',
    'manchestereveningnews.co.uk',
    '\.aol.co.uk',
    
    'homify.de',
    'modepilot.com',
    'welt.de',
    'teleschau.de',
    
    '750g.com',
    'bfmtv.fr',
    'bfmtv.com',
    'mamamia.com.au'
);

function get_country_code(){
    $host = $_SERVER['HTTP_HOST'];
    
    if(preg_match("#^([a-z]{2})\.#i", $host, $matches)){
        return $matches[1];
    }
    else{
        $country_host['smiexpress.ru']          = 'ru';
        $country_host['francais-express.com']   = 'fr';
        
        if(!isset($country_host[$host])){
            return 'us';
        }
        
        return $country_host[$host];
    }
}

function get_host_conf($config_var){
    $host = $_SERVER['HTTP_HOST'];
    
    if(preg_match("#^([a-z]{2}\.|)([a-z\d\.-]+)$#i", $host, $matches)){
        if(isset($config_var[$matches[2]])){
            return $config_var[$matches[2]];
        }
    }
    
    return $config_var['default'];
}


$config_var['default'] = get_host_conf($config_var);


$config['multidomaine']['main_host'] = [
    'smiexpress.ru',
    'fr.pressfrom.com',
    'de.pressfrom.com',
    'uk.pressfrom.com',
    'us.pressfrom.com',
    'ca.pressfrom.com',
    'au.pressfrom.com'
];

$config['multidomaine']['host_set'][$_SERVER['HTTP_HOST']] = get_country_code();


//===== Ru =====//
$config['multidomaine']['ru']['site_name_str']      = $config_var['default']['site_name'].' - Россия';
$config['multidomaine']['ru']['lang']               = 'ru';
$config['multidomaine']['ru']['logo_img']           = $config_var['default']['logo_img'];
$config['multidomaine']['ru']['logo_img_mobile']    = $config_var['default']['logo_img_mobile'];
$config['multidomaine']['ru']['e_mail']             = $config_var['default']['mail'];
$config['multidomaine']['ru']['host']               = 'smiexpress.ru';
$config['multidomaine']['ru']['contact_str']        = 'Контакты';
$config['multidomaine']['ru']['top_news_str']       = 'TOP Новости';
$config['multidomaine']['ru']['last_news_str']      = 'Последние Новости';
$config['multidomaine']['ru']['like_news_str']      = 'Смотрите также';
$config['multidomaine']['ru']['like_video_str']     = 'Тематическое видео';
$config['multidomaine']['ru']['serp_news_str']      = 'Похожее в сети';
$config['multidomaine']['ru']['comments_str']       = 'Комментарии';
$config['multidomaine']['ru']['source_str']         = 'Источник';
$config['multidomaine']['ru']['repost_news_str']    = 'Поделится Новостью в Соц. Сетях';
$config['multidomaine']['ru']['page_str']           = 'Страница';
$config['multidomaine']['ru']['month_ar']           = array( 1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
$config['multidomaine']['ru']['day_ar']             = array('Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота');
$config['multidomaine']['ru']['xml_yandex_url']     = 'https://xmlsearch.yandex.com/xmlsearch?user=mail@lalalay.com&key=03.1130000018332401:db8ac7bad789ba8f7aabca04b0aa6308&maxpassages=5&groupby=groups-on-page%3D15';
$config['multidomaine']['ru']['social_btn_list']    = 'vkontakte,facebook,twitter,odnoklassniki';
$config['multidomaine']['ru']['outwindow_str']      = 'Это интересно!';
$config['multidomaine']['ru']['lock_donor']         = $lock_host;
$config['multidomaine']['ru']['static_server']       = 'ru.static.lalalay.com';

//===== Fr =====//
$config['multidomaine']['fr']['site_name_str']      = $config_var['default']['site_name'].' - France';
$config['multidomaine']['fr']['lang']               = 'fr';
$config['multidomaine']['fr']['logo_img']           = $config_var['default']['logo_img'];
$config['multidomaine']['fr']['logo_img_mobile']    = $config_var['default']['logo_img_mobile'];
$config['multidomaine']['fr']['e_mail']             = $config_var['default']['mail'];
$config['multidomaine']['fr']['host']               = 'fr.pressfrom.com';
$config['multidomaine']['fr']['contact_str']        = 'Contact';
$config['multidomaine']['fr']['top_news_str']       = 'Actualités à la une';
$config['multidomaine']['fr']['last_news_str']      = 'Les Dernières Nouvelles';
$config['multidomaine']['fr']['like_news_str']      = 'Voir aussi';
$config['multidomaine']['fr']['like_video_str']     = 'Thématique de la vidéo';
$config['multidomaine']['fr']['serp_news_str']      = 'Semblable dans le réseau';
$config['multidomaine']['fr']['comments_str']       = 'Commentaires';
$config['multidomaine']['fr']['source_str']         = 'Source';
$config['multidomaine']['fr']['repost_news_str']    = 'Partager dans le Soc. Réseaux';
$config['multidomaine']['fr']['page_str']           = 'Page';
$config['multidomaine']['fr']['month_ar']           = array( 1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
$config['multidomaine']['fr']['day_ar']             = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
$config['multidomaine']['fr']['xml_yandex_url']     = 'https://xmlsearch.yandex.com/xmlsearch?user=mail@lalalay.com&key=03.1130000018332401:db8ac7bad789ba8f7aabca04b0aa6308&maxpassages=5&groupby=groups-on-page%3D15';
$config['multidomaine']['fr']['social_btn_list']    = 'facebook,twitter,gplus';
$config['multidomaine']['fr']['outwindow_str']      = 'C\'est intéressant!';
$config['multidomaine']['fr']['lock_donor']         = $lock_host;
$config['multidomaine']['fr']['static_server']       = 'fr.static.lalalay.com';


//===== De =====//
$config['multidomaine']['de']['site_name_str']      = $config_var['default']['site_name'].' - Deutschland';
$config['multidomaine']['de']['lang']               = 'de';
$config['multidomaine']['de']['logo_img']           = $config_var['default']['logo_img'];
$config['multidomaine']['de']['logo_img_mobile']    = $config_var['default']['logo_img_mobile'];
$config['multidomaine']['de']['e_mail']             = $config_var['default']['mail'];
$config['multidomaine']['de']['host']               = 'de.pressfrom.com';
$config['multidomaine']['de']['contact_str']        = 'Kontakte';
$config['multidomaine']['de']['top_news_str']       = 'Popular News';
$config['multidomaine']['de']['last_news_str']      = 'Aktuelle Nachrichten';
$config['multidomaine']['de']['like_news_str']      = 'Siehe auch';
$config['multidomaine']['de']['like_video_str']     = 'Aktuelle videos';
$config['multidomaine']['de']['serp_news_str']      = 'Ähnliches im Netz';
$config['multidomaine']['de']['comments_str']       = 'Kommentare';
$config['multidomaine']['de']['source_str']         = 'Quelle';
$config['multidomaine']['de']['repost_news_str']    = 'Teilen Sie Neuigkeiten in der SOC. Netzwerke';
$config['multidomaine']['de']['page_str']           = 'Seite';
$config['multidomaine']['de']['month_ar']           = array( 1=>'januar','februar','märz','april','mai','juni','juli','august','september','oktober','november','dezember');
$config['multidomaine']['de']['day_ar']             = array('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag');
$config['multidomaine']['de']['xml_yandex_url']     = 'https://xmlsearch.yandex.com/xmlsearch?user=mail@lalalay.com&key=03.1130000018332401:db8ac7bad789ba8f7aabca04b0aa6308&maxpassages=5&groupby=groups-on-page%3D15';
$config['multidomaine']['de']['social_btn_list']    = 'facebook,twitter,gplus';
$config['multidomaine']['de']['outwindow_str']      = 'Das ist interessant!';
$config['multidomaine']['de']['lock_donor']         = $lock_host;
$config['multidomaine']['de']['static_server']      = 'de.static.lalalay.com';


//===== Gb =====//
$config['multidomaine']['uk']['site_name_str']      = $config_var['default']['site_name'].' - United Kingdom';
$config['multidomaine']['uk']['lang']               = 'en';
$config['multidomaine']['uk']['logo_img']           = $config_var['default']['logo_img'];
$config['multidomaine']['uk']['logo_img_mobile']    = $config_var['default']['logo_img_mobile'];
$config['multidomaine']['uk']['e_mail']             = $config_var['default']['mail'];
$config['multidomaine']['uk']['host']               = 'uk.pressfrom.com';
$config['multidomaine']['uk']['contact_str']        = 'Contacts';
$config['multidomaine']['uk']['top_news_str']       = 'TOP News';
$config['multidomaine']['uk']['last_news_str']      = 'Latest News';
$config['multidomaine']['uk']['like_news_str']      = 'See also';
$config['multidomaine']['uk']['like_video_str']     = 'Topical videos';
$config['multidomaine']['uk']['serp_news_str']      = 'Similar from the Web';
$config['multidomaine']['uk']['comments_str']       = 'Comments';
$config['multidomaine']['uk']['source_str']         = 'Source';
$config['multidomaine']['uk']['repost_news_str']    = 'Share news in the SOC. Networks';
$config['multidomaine']['uk']['page_str']           = 'Page';
$config['multidomaine']['uk']['month_ar']           = array( 1=>'january','february','march','april','may','june','july','august','september','october','november','december');
$config['multidomaine']['uk']['day_ar']             = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$config['multidomaine']['uk']['xml_yandex_url']     = 'https://xmlsearch.yandex.com/xmlsearch?user=mail@lalalay.com&key=03.1130000018332401:db8ac7bad789ba8f7aabca04b0aa6308&maxpassages=5&groupby=groups-on-page%3D15';
$config['multidomaine']['uk']['social_btn_list']    = 'facebook,twitter,gplus';
$config['multidomaine']['uk']['outwindow_str']      = 'This is interesting!';
$config['multidomaine']['uk']['lock_donor']         = $lock_host;
$config['multidomaine']['uk']['static_server']      = 'uk.static.lalalay.com';


//===== US =====//
$config['multidomaine']['us'] = $config['multidomaine']['uk'];
$config['multidomaine']['us']['site_name_str']      = $config_var['default']['site_name'].' - US';
$config['multidomaine']['us']['e_mail']             = $config_var['default']['mail'];
$config['multidomaine']['us']['host']               = 'us.pressfrom.com';
$config['multidomaine']['us']['logo_img']           = $config_var['default']['logo_img'];
$config['multidomaine']['us']['logo_img_mobile']    = $config_var['default']['logo_img_mobile'];
$config['multidomaine']['us']['lock_donor']         = $lock_host;
$config['multidomaine']['us']['static_server']       = 'us.static.lalalay.com';


//===== CA =====//
$config['multidomaine']['ca'] = $config['multidomaine']['us'];
$config['multidomaine']['ca']['site_name_str']      = $config_var['default']['site_name'].' - Canada';
$config['multidomaine']['ca']['host']               = 'ca.pressfrom.com';
$config['multidomaine']['ca']['lock_donor']         = $lock_host;
$config['multidomaine']['ca']['static_server']      = 'ca.static.lalalay.com';


//===== AU =====//
$config['multidomaine']['au'] = $config['multidomaine']['us'];
$config['multidomaine']['au']['site_name_str']      = $config_var['default']['site_name'].' - Australia';
$config['multidomaine']['au']['host']               = 'au.pressfrom.com';
$config['multidomaine']['au']['lock_donor']         = $lock_host;
$config['multidomaine']['au']['static_server']      = 'au.static.lalalay.com';