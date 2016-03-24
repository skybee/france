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
                        //.'<span>Смотрите также:</span> '
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