<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rss_url_lib
{
    
    function __construct() {
        
    }
    
    function get_url( $xml_str )
    {
        libxml_use_internal_errors( FALSE ); //отключение вывода ошибок XML
        $xml = simplexml_load_string( $xml_str, null, LIBXML_NOCDATA );
        
        if( !$xml ) return FALSE;
        
        $result_ar = array();
        
        foreach ($xml->channel->item as $news) 
        {
            $news = (array) $news;
            
            $category = 'N/A';
            
            if( isset($news['category']) && is_string( $news['category'] ) )
                $category = $news['category'];
            
            $result_ar[] = array( 'category' => $category, 'url' => $news['link'] );
        }
        
        return $result_ar;
    }
    
    function get_cat_id( $donor_url, $url, $cat = false ){
        
        $cat_ar['news']['ukraine']      = 4;
        $cat_ar['news']['politics']     = 5;
        $cat_ar['news']['finance']      = 6;
        $cat_ar['news']['world']        = 7;
        $cat_ar['news']['sport']        = 8;
        $cat_ar['news']['science']      = 9;
        $cat_ar['news']['health']       = 10;
        $cat_ar['news']['showbiz']      = 11;
        
        $cat_id = FALSE;
        
        if( $donor_url == 'http://tsn.ua/rss/' || $donor_url == 'http://ru.tsn.ua/rss/' ){
            if( stripos($url, 'tsn.ua/sport')           !== false ) $cat_id = $cat_ar['news']['sport'];
            elseif( stripos($url, 'tsn.ua/svit')        !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($url, 'tsn.ua/ukrayina')    !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($url, 'tsn.ua/groshi')      !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, 'tsn.ua/nauka_it')    !== false ) $cat_id = $cat_ar['news']['science'];
            elseif( stripos($url, 'tsn.ua/politika')    !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($url, 'tsn.ua/glamur/')     !== false ) $cat_id = $cat_ar['news']['showbiz'];
        }
        elseif( $donor_url == 'http://www.unn.com.ua/rss/news_ru.xml' ){
            if( stripos($cat, 'Политика')           !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($cat, 'Экономика')          !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($cat, 'Технологии')         !== false ) $cat_id = $cat_ar['news']['science'];
            elseif( stripos($cat, 'Мир')                !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($cat, 'Спорт')              !== false ) $cat_id = $cat_ar['news']['sport'];
            elseif( stripos($cat, 'Здоровье')           !== false ) $cat_id = $cat_ar['news']['health'];
            elseif( stripos($cat, 'Культура')           !== false ) $cat_id = $cat_ar['news']['showbiz'];
        }
        elseif( $donor_url == 'http://rss.unian.net/site/news_ukr.rss' ){
            if( stripos($cat, 'Політика')               !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($cat, 'Україна')            !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($cat, 'Економіка')          !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($cat, 'Світ')               !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($cat, "Здоров'я")           !== false ) $cat_id = $cat_ar['news']['health']; 
            elseif( stripos($cat, 'Спорт')              !== false ) $cat_id = $cat_ar['news']['sport'];
        }
        elseif( $donor_url == 'http://rss.unian.net/site/news_rus.rss' ){
//            if( stripos($cat, 'Политика')               !== false ) $cat_id = $cat_ar['news']['politics'];
//            elseif( stripos($cat, 'Украина')            !== false ) $cat_id = $cat_ar['news']['ukraine'];
//            elseif( stripos($cat, 'Экономика')          !== false ) $cat_id = $cat_ar['news']['finance'];
//            elseif( stripos($cat, 'Мир')                !== false ) $cat_id = $cat_ar['news']['world'];
//            elseif( stripos($cat, "Здоровье")           !== false ) $cat_id = $cat_ar['news']['health']; 
//            elseif( stripos($cat, 'Спорт')              !== false ) $cat_id = $cat_ar['news']['sport'];
            if( stripos($url, '/sport/')                !== false ) $cat_id = $cat_ar['news']['sport'];
            elseif( stripos($url, '/world/')            !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($url, '/society/')          !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($url, '/science/')          !== false ) $cat_id = $cat_ar['news']['science'];
            elseif( stripos($url, '/politics/')         !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($url, '/finance/')          !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/energetics/')       !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/stockmarket/')      !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/transport/')        !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/realestate/')       !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/agro/')             !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/industry/')         !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/soc/')              !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, 'health.unian.net/country/')      !== false ) $cat_id = $cat_ar['news']['health'];
            elseif( stripos($url, 'health.unian.net/cancer/')       !== false ) $cat_id = $cat_ar['news']['health'];
            elseif( stripos($url, 'health.unian.net/worldnews/')    !== false ) $cat_id = $cat_ar['news']['health'];
            
        }
        elseif( $donor_url == 'http://www.interfax.com.ua/rus/rss/' ){
            if( stripos($url, 'rus/eco')                !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, 'rus/main')           !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($url, 'rus/pol')            !== false ) $cat_id = $cat_ar['news']['politics'];
        }
        elseif( $donor_url == 'http://www.segodnya.ua/xml/rss.html' ){
            if( stripos($url, 'sport.segodnya.ua')      !== false ) $cat_id = $cat_ar['news']['sport'];
            elseif( stripos($url, 'ua/culture')         !== false ) $cat_id = $cat_ar['news']['showbiz'];
            elseif( stripos($url, 'ua/politics')        !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($url, 'ua/economics')       !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, 'ua/science')         !== false ) $cat_id = $cat_ar['news']['science'];
        }
        elseif( $donor_url == 'http://delo.ua/news/rss/index.xml' ){
            if( stripos($cat, 'Политика')               !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($cat, 'Компании')           !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($cat, 'Экономика')          !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($cat, 'Рынки')              !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($cat, 'Банки')              !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($cat, 'Общество')           !== false ) $cat_id = $cat_ar['news']['ukraine'];
        }
        elseif( $donor_url == 'http://focus.ua/rss/ru.xml' ){
            if( stripos($url, 'ua/economy/')            !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, 'ua/foreign/')        !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($url, 'ua/sport/')          !== false ) $cat_id = $cat_ar['news']['sport'];
            elseif( stripos($url, 'ua/health/')         !== false ) $cat_id = $cat_ar['news']['health'];
            elseif( stripos($url, 'ua/politics/')       !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($url, 'ua/incident/')       !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($url, 'ua/society/')        !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($url, 'ua/culture/')        !== false ) $cat_id = $cat_ar['news']['showbiz'];
            elseif( stripos($url, 'ua/tech/')           !== false ) $cat_id = $cat_ar['news']['science'];
        }
        elseif( $donor_url == 'http://news.liga.net/all/rss.xml' ){
            if( stripos($url, '/news/culture/')         !== false ) $cat_id = $cat_ar['news']['showbiz'];
            elseif( stripos($url, '/news/politics/')    !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($url, '/news/world/')       !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($url, '/news/sport/')       !== false ) $cat_id = $cat_ar['news']['sport'];
            elseif( stripos($url, '/news/society/')     !== false ) $cat_id = $cat_ar['news']['ukraine'];
        }
        elseif( $donor_url == 'http://isport.ua/hnd/rss.ashx?image=0' ){
            $cat_id = $cat_ar['news']['sport'];
        }
        elseif( $donor_url == 'http://k.img.com.ua/rss/ru/news.xml' ){
            if( stripos($url, '/business/financial/')           !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/business/taxes/')           !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/business/rynki/')           !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/business/companies/')       !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/business/mmedia_and_adv/')  !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/business/')                 !== false ) $cat_id = $cat_ar['news']['finance'];
            elseif( stripos($url, '/worldabus/')                !== false ) $cat_id = $cat_ar['news']['ukraine'];
            elseif( stripos($url, '/ukraine/politics/')         !== false ) $cat_id = $cat_ar['news']['politics'];
            elseif( stripos($url, '/world/')                    !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($url, '/russia/')                   !== false ) $cat_id = $cat_ar['news']['world'];
            elseif( stripos($url, '/business/web/')             !== false ) $cat_id = $cat_ar['news']['science'];
            elseif( stripos($url, '/sport/')                    !== false ) $cat_id = $cat_ar['news']['sport'];
        }   
        
        return $cat_id;
    }
        
}