<?php if (!defined('BASEPATH')) exit('No direct script access allowed');





function getDescriptionFromText( string &$text, integer $length ){
}


function botRelNofollow(){
    
//    $ip = $_SERVER['HTTP_X_REAL_IP'];
//    $pattern    = "#^(127\.0|66\.249|203\.208|72\.14|209\.85)\.\d{1,3}\.\d{1,3}#i";
    $pattern = "#(Yandex|google|rogerbot|Exabot|MJ12bot|DotBot|Gigabot|AhrefsBot|Yahoo|msnbot|bingbot|SolomonoBot|SemrushBot|Blekkobot)#i";
 
    $rel = '';
    
//    if( preg_match($pattern, $ip) ){
//        $rel = ' rel="nofollow" ';
//    }
    
    if( preg_match( $pattern, $_SERVER['HTTP_USER_AGENT']) ){
        $rel = ' rel="nofollow" ';
    }
    
    return $rel;
}

function serpDataFromJson($json)
{
    if(empty($json)){
        return false;
    }
    
    $data = json_decode($json, true);
    
    if(count($data)<1)
    {
        $json = stripcslashes($json);
        $data = json_decode($json, true); 
    }
    
    return $data;
}

function insertLikeArticleInTxt($text, $likeList)
{   
    return $text; # -- TMP -- #
    
    if(!isset($likeList[0])){
        return $text; 
    }

//    echo $likeList[0]['text'];
    
    $newsUrl    = "/{$likeList[0]['full_uri']}-{$likeList[0]['id']}-{$likeList[0]['url_name']}/";
    
    $likeTitle  = str_replace('$', '&dollar;', $likeList[0]['title']);
    $likeText   = str_replace('$', '&dollar;', $likeList[0]['text']);

    $search     = "/([\s\S]{500}(<\/p>|<br.{0,2}>\s*<br.{0,2}>))/i";
    $replace    = "$1 \n "
                . '<style> '
                    . '@media(max-width: 980px){ #left div.single div.mobile-in-txt .mobile-intxt-grey{width: 468px; height: 60px;} } '
                    . '@media(max-width: 540px){ #left div.single div.mobile-in-txt .mobile-intxt-grey{width: 320px; height: 100px;} } '
                    . '@media(max-width: 340px){ #left div.single div.mobile-in-txt .mobile-intxt-grey{width: 234px; height: 60px;} } '
                . '</style> '
                .'<h2 class="look_more_hdn" rel="'.$newsUrl.'"><span>Смотрите также:</span> '.$likeTitle
                    ."<span class=\"gAd\" data=\"mobile greyInTxt\"></span> \n  "
                . "</h2>\n";
    
    $replace   .= '<p class="look_more_hdn">'."\n";

    if(!empty($likeList[0]['main_img'])){
        $replace .= '<img src="/upload/images/small/'.$likeList[0]['main_img'].'" alt="'.$likeTitle.'" onerror="imgError(this);" />'."\n";
    }
    $replace   .= $likeText."\n "
            . "<span style=\"display:block; margin-top:15px;\"> \n"
            . "<span class=\"gAd\" data=\"content greyInTxt\"></span> \n "
            . "</span> \n"
            . "</p>\n";

    $text = preg_replace($search, $replace, $text, 1);

    return $text;
}

function insertLikeArtInTxt($text, $likeList, $likeSerpAr)
{
    if(!isset($likeList[0])){
        return $text; 
    }
    
//    print_r($likeSerpAr);
    
    $i =0; 
    $ii=0; //для LikeSerp
    foreach ($likeList as $likeArticle)
    {
        $newsUrl        = "/{$likeArticle['full_uri']}-{$likeArticle['id']}-{$likeArticle['url_name']}/";
        $likeTitle      = str_replace('$', '&dollar;', $likeArticle['title']);
        $likeText       = str_replace('$', '&dollar;', $likeArticle['description']);
        $likeSerpTxt    = '';
        if(is_array($likeSerpAr) AND isset($likeSerpAr[$ii]))
        {
            $likeSerpTxt    = "<p>\n".$likeSerpAr[$ii]['text']."\n</p>\n";
            
            if(isset($likeSerpAr[$ii+1]))
            {
                $likeSerpTxt   .= "<p>\n".$likeSerpAr[$ii+1]['text']."\n</p>";
            }
        }
        
        $likeArtHtml =  "\n"
                        .' <h2 class="look_more_hdn" rel="'.$newsUrl.'">'
                        . '<img src="/upload/images/small/'.$likeArticle['main_img'].'" alt="" onerror="imgError(this);" class="look_more_img_mobile"/>'."\n"
                        .$likeTitle
                        . "</h2>\n"
                        . '<p class="look_more_hdn"> '."\n "
                        . "\t".'<span class="lmh_height_txt">'."\n"
                        . '<img src="/upload/images/real/'.$likeArticle['main_img'].'" alt="'.$likeTitle.'" onerror="imgError(this);"/>'."\n"
                        . $likeText."\n "
                        . "\t</span>\n</p>\n "
                        .'<blockquote class="serp-blockquote">'."\n".$likeSerpTxt."\n".'</blockquote>'."\n";
        
        if($i==0)
        {
            $likeArtHtml = "\n".'<span class="first-like-art-in-txt">'.$likeArtHtml.'</span>';
        }
        
//        $text = str_ireplace('<!--likeMarker-->', $likeArtHtml, $text, 1);
        $text = preg_replace("#<\!--likeMarker-->#iu", $likeArtHtml, $text, 1);
        
        $i++; 
        $ii = $ii+2;
    }
    
    $lastLikeSerp = $likeList[count($likeList)-1];
    
    $text .= "\n".'<p class="serp-blockquote">'."\n".$lastLikeSerp['title'].".<br />\n".$lastLikeSerp['description']."\n</p>\n";
    
    return $text;
}

function addResponsiveVideoTag($text){
    $pattern = "#(<(iframe|embed)[\s\S]+?(youtube.com|vimeo.com|tsn.ua)[\s\S]+?</(iframe|embed)>)#i";
    
    $text = preg_replace($pattern, "<div class=\"respon_video\">$1</div>", $text);
    
    return $text;
}

function cctv_article_linkator( $text ){ 
    
    return $text;
    
    $pattern_list['http://cctv-pro.com.ua/category/CCTV-Cameras/']                      = "#(купол[а-я]*|уличн[а-я]+|поворотн[а-я]+|наружн[а-я]+|)\s*(ip-|cctv-|ptz-|)(теле|видео|)камер[а-я]*\s*((видео|)наблюден[а-я]+|)#ui";
    $pattern_list['http://cctv-pro.com.ua/category/CCTV-Kits/']                         = "#комплект.{1,20}видеонаблюден[а-я]+#ui";
    $pattern_list['http://cctv-pro.com.ua/#home']                                       = "#(устан[а-я]*|монт[а-я]*|инстал[а-я]*)\s*(систем.{0,20}|.{0,20}камер[а-я]*|)\s*видеонаблюден[а-я]+#ui";
    $pattern_list['http://cctv-pro.com.ua/']                                            = "#(систем.{1,20}|)видеонаблюден[а-я]+#ui";
    $pattern_list['http://cctv-pro.com.ua/category/CCTV-DVR/']                          = "#(цифров[а-я]*|сетев[а-я]*|ip-|)\s*видеорегистр[а-я]*#ui";
    $pattern_list['http://cctv-pro.com.ua/category/Intercom/#home']                     = "#(устан[а-я]*|монт[а-я]*)\s*(видео|аудио|)\s*домофон[а-я]*\s*(.{0,20}(дом[а-я]*|офис[а-я]*|квартир[а-я]*|commax)|)#ui";
    $pattern_list['http://cctv-pro.com.ua/category/Intercom/']                          = "#(панель|монитор|)\s*(видео|аудио|)\s*домофон[а-я]*\s*(.{0,20}(дом[а-я]*|офис[а-я]*|квартир[а-я]*|commax)|)#ui";

    
    // -== House Control ==- //
    $conditionPattern = "кондиционер[а-я]{0,4}";
    $pattern_list['http://house-control.org.ua/category/kondicionery/']                 = "#\s[а-я\s-]{7,25}\s+{$conditionPattern}#ui";
    $pattern_list['http://house-control.org.ua/category/kondicionery/#second']          = "#{$conditionPattern}\s+[а-я\s-]{7,25}\s#ui";
    $pattern_list['http://house-control.org.ua/category/kondicionery/#third']           = "#\s[а-я-]{5,20}\s+{$conditionPattern}\s+[а-я-]{5,20}#ui";
    
    $pattern_list['http://house-control.org.ua/category/ohranna_pozharnaja_signalizacija/']     = "#(систем[а-я]*.{0,15}|)\s*(охран[а-я]*|(охран[а-я]*.{0,3}|)пожар[а-я]*|)\s*сигнализ[а-я]*\s*(.{0,20}(дом[а-я]*|офис[а-я]*|квартир[а-я]*|магазин[а-я]*|склад[а-я]*)|)#ui";
    $pattern_list['http://house-control.org.ua/category/kontrol_dostupa/']                      = "#(систем[а-я]*.{0,15}|)\s*контр[а-я]*\s*(.{0,10}управ[а-я]*|)\s*доступ[а-я]*#ui";
    
    $pattern_list['http://house-control.org.ua/category/kamera/']                               = "#(купол[а-я]*|уличн[а-я]+|поворотн[а-я]+|наружн[а-я]+|)\s*(ip-|cctv-|ptz-|)(теле|видео|)камер[а-я]*\s*((видео|)наблюден[а-я]+|)#ui";
    $pattern_list['http://house-control.org.ua/category/cctv-komplekt-videonabludenie/']        = "#комплект.{1,20}видеонаблюден[а-я]+#ui";
    $pattern_list['http://house-control.org.ua/info/services/']                                 = "#(устан[а-я]*|монт[а-я]*|инстал[а-я]*)\s*(систем.{0,20}|.{0,20}камер[а-я]*|)\s*видеонаблюден[а-я]+#ui";
    $pattern_list['http://house-control.org.ua/category/cctv-systems/']                         = "#(систем.{1,20}|)видеонаблюден[а-я]+#ui";
    $pattern_list['http://house-control.org.ua/category/multiformatnye-registratory/']          = "#(цифров[а-я]*|сетев[а-я]*|ip-|)\s*видеорегистр[а-я]*#ui";
    $pattern_list['http://house-control.org.ua/info/services/#domofone']                        = "#(устан[а-я]*|монт[а-я]*)\s*(видео|аудио|)\s*домофон[а-я]*\s*(.{0,20}(дом[а-я]*|офис[а-я]*|квартир[а-я]*|commax)|)#ui";
    $pattern_list['http://house-control.org.ua/category/domofony/']                             = "#(панель|монитор|)\s*(видео|аудио|)\s*домофон[а-я]*\s*(.{0,20}(дом[а-я]*|офис[а-я]*|квартир[а-я]*|commax)|)#ui";
    
    
    $key = 1;
    foreach( $pattern_list as $url => $pattern ){
        if( !empty($pattern) ){
            if( preg_match($pattern, $text, $keyword_ar ) ){
//                echo '<pre>'.print_r($keyword_ar[0],1).'</pre>';
                $replace_key = "#$key#";
                
                $replace_ar['search'][$key]     = $replace_key;
                $replace_ar['replace'][$key]    = ' <a target="_blaank" href="'.$url.'">'.$keyword_ar[0].'</a> ';
                
                $text = preg_replace("#{$keyword_ar[0]}#ui", $replace_key, $text, 1);
            }
        }
        $key++;
    }
    
    if( isset($replace_ar) && $replace_ar['search'] > 1 ){
        $text = str_ireplace( $replace_ar['search'], $replace_ar['replace'], $text);
    }
    
    return $text;
}

function get_sape_donor_link(){
    
    $file       = 'sape_a_link_donor.txt';
    $rand_str   = $_SERVER['REQUEST_URI'];
    
    
    if(!is_file($file)){return '';};
    
    $link_ar = file( $file );
    
    mt_srand( abs( crc32($rand_str) ) );
    $rndInt     = mt_rand(1,1000);
    $rndInt2    = mt_rand(1,1000);
    $return     = $link_ar[mt_rand(0, count($link_ar)-1)];
    mt_srand();
    
    if($rndInt <= 50){ // return Link
        
        if($rndInt2 <= 500) // Add nofollow
        {
            $return = preg_replace("#<a #", '<a rel="nofollow" ', $return);
        }
        
        return $return;
    }
    else{
        return '';
    }
}