<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['multidomaine']['host_set']['smiexpress.ru']            = 'ru';
$config['multidomaine']['host_set']['francais-express.com']     = 'fr';
$config['multidomaine']['host_set']['de.francais-express.com']  = 'de';
$config['multidomaine']['host_set']['en.francais-express.com']  = 'gb';
$config['multidomaine']['host_set']['us.pressfrom.com']         = 'us';
$config['multidomaine']['host_set']['ca.pressfrom.com']         = 'ca';
$config['multidomaine']['host_set']['au.pressfrom.com']         = 'au';
$config['multidomaine']['host_set']['tr.pressfrom.com']         = 'tr';

//-------=== Aliases ===-------//
$config['multidomaine']['host_set']['express.lh']               = 'ru';
$config['multidomaine']['host_set']['ru.pressfrom.com']         = 'ru-alias';
$config['multidomaine']['host_set']['fr.pressfrom.com']         = 'fr-alias';
$config['multidomaine']['host_set']['de.pressfrom.com']         = 'de-alias';
$config['multidomaine']['host_set']['uk.pressfrom.com']         = 'uk-alias';

$config['multidomaine']['aliases'] = array(
    'express.lh',
    'ru.pressfrom.com',
    'fr.pressfrom.com',
    'de.pressfrom.com',
    'uk.pressfrom.com'
);
//-------=== /Aliases ===-------//



//===== Ru =====//
$config['multidomaine']['ru']['site_name_str']      = 'СМИ Express';
$config['multidomaine']['ru']['lang']               = 'ru';
$config['multidomaine']['ru']['logo_img']           = 'logo-ru.jpg';
$config['multidomaine']['ru']['logo_img_mobile']    = 'logo-ru-mobile.png';
$config['multidomaine']['ru']['e_mail']             = 'mail@smiexpress.ru';
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
$config['multidomaine']['ru']['lock_donor']         = array();


//===== Tr =====//
$config['multidomaine']['tr']['site_name_str']      = 'Press From - Türkiye';
$config['multidomaine']['tr']['lang']               = 'tr';
$config['multidomaine']['tr']['logo_img']           = 'logo-pressfrom-1.png';
$config['multidomaine']['tr']['logo_img_mobile']    = 'logo-fr-mobile.png';
$config['multidomaine']['tr']['e_mail']             = 'mail@pressfrom.com';
$config['multidomaine']['tr']['host']               = 'tr.pressfrom.com';
$config['multidomaine']['tr']['contact_str']        = 'Ksontaklar';
$config['multidomaine']['tr']['top_news_str']       = 'TOP Haberleri';
$config['multidomaine']['tr']['last_news_str']      = 'Son Haberler';
$config['multidomaine']['tr']['like_news_str']      = 'Ayrıca bakınız';
$config['multidomaine']['tr']['like_video_str']     = 'Tematik bir video';
$config['multidomaine']['tr']['serp_news_str']      = "Web'den Benzer";
$config['multidomaine']['tr']['comments_str']       = 'Yorumlar';
$config['multidomaine']['tr']['source_str']         = 'Kaynak';
$config['multidomaine']['tr']['repost_news_str']    = 'Haberleri yayınla';
$config['multidomaine']['tr']['page_str']           = 'Sayfa';
$config['multidomaine']['tr']['month_ar']           = array( 1=>'Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
$config['multidomaine']['tr']['day_ar']             = array('Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi');
$config['multidomaine']['tr']['xml_yandex_url']     = 'https://xmlsearch.yandex.com/xmlsearch?user=mail@lalalay.com&key=03.1130000018332401:db8ac7bad789ba8f7aabca04b0aa6308&maxpassages=5&groupby=groups-on-page%3D15';
$config['multidomaine']['tr']['social_btn_list']    = 'facebook,twitter,gplus';
$config['multidomaine']['tr']['outwindow_str']      = 'Bu ilginç!';
$config['multidomaine']['tr']['lock_donor']         = array();


//===== Fr =====//
$config['multidomaine']['fr']['site_name_str']      = 'Français Express';
$config['multidomaine']['fr']['lang']               = 'fr';
$config['multidomaine']['fr']['logo_img']           = 'logo-fr.jpg';
$config['multidomaine']['fr']['logo_img_mobile']    = 'logo-fr-mobile.png';
$config['multidomaine']['fr']['e_mail']             = 'mail@francais-express.com';
$config['multidomaine']['fr']['host']               = 'francais-express.com';
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
$config['multidomaine']['fr']['lock_donor']         = array('750g.com');


//===== De =====//
$config['multidomaine']['de']['site_name_str']      = 'Deutsch Express';
$config['multidomaine']['de']['lang']               = 'de';
$config['multidomaine']['de']['logo_img']           = 'logo-fr.jpg';
$config['multidomaine']['de']['logo_img_mobile']    = 'logo-fr-mobile.png';
$config['multidomaine']['de']['e_mail']             = 'mail@francais-express.com';
$config['multidomaine']['de']['host']               = 'de.francais-express.com';
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
$config['multidomaine']['de']['lock_donor']         = array('homify.de','modepilot.com','welt.de');


//===== Gb =====//
$config['multidomaine']['gb']['site_name_str']      = 'British Express';
$config['multidomaine']['gb']['lang']               = 'en';
$config['multidomaine']['gb']['logo_img']           = 'logo-fr.jpg';
$config['multidomaine']['gb']['logo_img_mobile']    = 'logo-fr-mobile.png';
$config['multidomaine']['gb']['e_mail']             = 'mail@francais-express.com';
$config['multidomaine']['gb']['host']               = 'en.francais-express.com';
$config['multidomaine']['gb']['contact_str']        = 'Contacts';
$config['multidomaine']['gb']['top_news_str']       = 'TOP News';
$config['multidomaine']['gb']['last_news_str']      = 'Latest News';
$config['multidomaine']['gb']['like_news_str']      = 'See also';
$config['multidomaine']['gb']['like_video_str']     = 'Topical videos';
$config['multidomaine']['gb']['serp_news_str']      = 'Similar from the Web';
$config['multidomaine']['gb']['comments_str']       = 'Comments';
$config['multidomaine']['gb']['source_str']         = 'Source';
$config['multidomaine']['gb']['repost_news_str']    = 'Share news in the SOC. Networks';
$config['multidomaine']['gb']['page_str']           = 'Page';
$config['multidomaine']['gb']['month_ar']           = array( 1=>'january','february','march','april','may','june','july','august','september','october','november','december');
$config['multidomaine']['gb']['day_ar']             = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$config['multidomaine']['gb']['xml_yandex_url']     = 'https://xmlsearch.yandex.com/xmlsearch?user=mail@lalalay.com&key=03.1130000018332401:db8ac7bad789ba8f7aabca04b0aa6308&maxpassages=5&groupby=groups-on-page%3D15';
$config['multidomaine']['gb']['social_btn_list']    = 'facebook,twitter,gplus';
$config['multidomaine']['gb']['outwindow_str']      = 'This is interesting!';
$config['multidomaine']['gb']['lock_donor']         = array('telegraph.co.uk','theguardian.com', 'independent.co.uk', 'standard.co.uk', 'mirror.co.uk', 'birminghammail.co.uk', 'liverpoolecho.co.uk','manchestereveningnews.co.uk','\.aol.co.uk');


//===== US =====//
$config['multidomaine']['us'] = $config['multidomaine']['gb'];
$config['multidomaine']['us']['site_name_str']      = 'Press From - US';
$config['multidomaine']['us']['e_mail']             = 'mail@pressfrom.com';
$config['multidomaine']['us']['host']               = 'us.pressfrom.com';
$config['multidomaine']['us']['logo_img']           = 'logo-pressfrom-1.png';
$config['multidomaine']['us']['logo_img_mobile']    = 'logo-fr-mobile.png';
$config['multidomaine']['us']['lock_donor']         = array();


//===== CA =====//
$config['multidomaine']['ca'] = $config['multidomaine']['us'];
$config['multidomaine']['ca']['site_name_str']      = 'Press From - Canada';
$config['multidomaine']['ca']['host']               = 'ca.pressfrom.com';
$config['multidomaine']['ca']['lock_donor']         = array();


//===== AU =====//
$config['multidomaine']['au'] = $config['multidomaine']['us'];
$config['multidomaine']['au']['site_name_str']      = 'Press From - Australia';
$config['multidomaine']['au']['host']               = 'au.pressfrom.com';
$config['multidomaine']['au']['lock_donor']         = array();


//-------=== Aliases ===-------//

//===== RU =====//
$config['multidomaine']['ru-alias'] = $config['multidomaine']['ru'];
$config['multidomaine']['ru-alias']['site_name_str']    = 'Press From - Russia';
$config['multidomaine']['ru-alias']['e_mail']           = $config['multidomaine']['us']['e_mail'];
$config['multidomaine']['ru-alias']['logo_img']         = $config['multidomaine']['us']['logo_img'];
$config['multidomaine']['ru-alias']['logo_img_mobile']  = $config['multidomaine']['us']['logo_img_mobile'];

//===== FR =====//
$config['multidomaine']['fr-alias'] = $config['multidomaine']['fr'];
$config['multidomaine']['fr-alias']['site_name_str']    = 'Press From - France';
$config['multidomaine']['fr-alias']['e_mail']           = $config['multidomaine']['us']['e_mail'];
$config['multidomaine']['fr-alias']['logo_img']         = $config['multidomaine']['us']['logo_img'];
$config['multidomaine']['fr-alias']['logo_img_mobile']  = $config['multidomaine']['us']['logo_img_mobile'];


//===== DE =====//
$config['multidomaine']['de-alias'] = $config['multidomaine']['de'];
$config['multidomaine']['de-alias']['site_name_str']    = 'Press From - Deutschland';
$config['multidomaine']['de-alias']['e_mail']           = $config['multidomaine']['us']['e_mail'];
$config['multidomaine']['de-alias']['logo_img']         = $config['multidomaine']['us']['logo_img'];
$config['multidomaine']['de-alias']['logo_img_mobile']  = $config['multidomaine']['us']['logo_img_mobile'];

//===== GB =====//
$config['multidomaine']['uk-alias'] = $config['multidomaine']['gb'];
$config['multidomaine']['uk-alias']['site_name_str']    = 'Press From - United Kingdom';
$config['multidomaine']['uk-alias']['e_mail']           = $config['multidomaine']['us']['e_mail'];
$config['multidomaine']['uk-alias']['logo_img']         = $config['multidomaine']['us']['logo_img'];
$config['multidomaine']['uk-alias']['logo_img_mobile']  = $config['multidomaine']['us']['logo_img_mobile'];

//-------=== /Aliases ===-------//