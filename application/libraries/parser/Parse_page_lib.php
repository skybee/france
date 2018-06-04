<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Parse_page_lib{
   
    function get_data( $html, $donorData ){
        
        $parseClass = $this->selectClass( $donorData['host'] );
        if( !$parseClass ) return false;
        $parseObj   = new $parseClass( $donorData );
        
        return  $parseObj->get_data($html);
    }
    
    private function selectClass($host){
        switch( $host ){
            case 'tsn.ua':                      return 'parseTsn';
            case 'korrespondent.net' :          return 'parseKorrespondent';
            case 'www.segodnya.ua' :            return 'parseSegodnya';
            case 'www.unn.com.ua':              return 'parseUnn';
            case 'www.unian.net' :              return 'parseUnian';
            case 'liga.net' :                   return 'parseNewsLiga';
            case 'interfax.com.ua' :            return 'parseInterfax';
            case 'sport.segodnya.ua' :          return 'parseSegodnya';
            case 'delo.ua' :                    return 'parseDelo';
            case 'focus.ua' :                   return 'parseFocus';
            case 'isport.ua' :                  return 'parseIsport';
            case 'compulenta.computerra.ru':    return 'parseCompulenta';
            case 'itc.ua':                      return 'parseItc';
            case 'habrahabr.ru':                return 'parseHabr'; 
            case '4pda.ru':                     return 'parse4PDA';
            case 'www.computerra.ru':           return 'parseComputerra';    
            case 'supreme2.ru':                 return 'parseSupreme';
            case 'hochu.ua':                    return 'parseHochu';    
            case 'www.goodhouse.ru':            return 'parseGoodhous';
            case 'lady.tsn.ua':                 return 'parseLadyTsnUa';
            case 'www.womenshealthmag.com':     return 'parseWomensHealthMag';
            case 'www.msn.com':                 return 'parseMsn';    
            default: return false;
        }
    }
    
}


class cleanDOM{
    
    private $DOM;
    
    function __construct( &$dom ) {
        $this->DOM = $dom;
    }
    
    function delSingle($selector, $key){
        if( is_object( $this->DOM->find($selector, $key) ) ){
            $this->DOM->find($selector, $key)->outertext = '';
        }
    }
    
    function delAll( $selector ){
        if( is_array( $this->DOM->find($selector) ) ){
            foreach( $this->DOM->find($selector) as $nextElement ){
                $nextElement->outertext = '';
            } 
        }
    }
    
    function delAllWrapper($selector){
        if( is_array( $this->DOM->find($selector) ) ){
            foreach( $this->DOM->find($selector) as $nextElement ){
                $nextElement->outertext = $nextElement->innertext;
            } 
        }
    }
}


abstract class parse_page{
    
    protected $donorData, $cleaner, $html_obj, $data = array('img'=>false,'title'=>false,'description'=>false,'text'=>false,'date'=>false,'canonical'=>false);
    
    function __construct( $donorData ) {
        $this->donorData = $donorData;
    }
    
    function get_data( $html ){
        
        $html = $this->predParseHTML( $html );
        $this->html_obj = str_get_html($html);
        if( !is_object($this->html_obj) ) return false;
        $this->cleaner  = new cleanDOM( $this->html_obj );
        $this->parseDOM();
        $this->html_obj->clear();
        
        unset( $this->html_obj );
        
        $this->data['text'] =  video_replace_lib::get_video_tags( $this->data['text'] );
        
        return $this->data;
    }
    
    function predParseHTML( $html ){ return $html; }
    
    abstract protected function parseDOM();
    
    protected function getNbrMonthFromStr( $str ){
        $patternAr = array(1=>'янва','февр','март','апрел','ма(й|я)','июн','июл','август','сентяб','октяб','ноябр','декабр');
        
        foreach( $patternAr as $mNmbr => $pattern ){
            $pattern = "#".$pattern."#iu";
            if( preg_match($pattern, $str) ){
                if( $mNmbr < 10 ) $mNmbr = '0'.$mNmbr;
                return $mNmbr;
            }
        }
        
        return 5;
    }
    
}
    

class parseTsn extends parse_page{
    
    function parseDOM(){
        
        if( is_object( $this->html_obj->find('.photo_descr',0) ) )
            $this->html_obj->find('.photo_descr',0)->outertext = '';
        
        if( is_object( $this->html_obj->find('span.v_info',0) ) ) //иконка для видео
            $this->html_obj->find('span.v_info',0)->outertext = '';
        
        if( is_object($this->html_obj->find('#news_text .image',0)) ){
            $this->data['img']      = $this->html_obj->find('#news_text .image',0)->href;
            $this->html_obj->find('#news_text .image',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('h2.descr',0) ) )
            $this->data['text']     = '<h2>'.$this->html_obj->find('h2.descr',0)->innertext.'</h2>';
        
        if( is_object( $this->html_obj->find('#news_text',0) ) ){
            $this->data['text']    .= $this->html_obj->find('#news_text',0)->innertext;
            
            //вставка видео из конца текста, если есть
            if( !is_object( $this->html_obj->find('#news_text',0)->find('.v_player', 0) ) && is_object( $this->html_obj->find('.v_player',0) ) ){
//                $this->data['text'] .= $this->html_obj->find('.v_player',0)->outertext;
                $html = $this->html_obj->find('body',0)->innertext;
                preg_match("#<div class='v_player' id='video_player'></div>[\s]+<script[\s\S]+?addVariable\('media_id', '[\d]+'\);[\s\S]+?</script>#iu", $html, $videoJsArr );
                $this->data['text'] .= "\n\n".$videoJsArr[0];
            }
        }
        
//        $this->data['text']         = preg_replace("#<p><strong>[\s\S]{4,20}:[\s]*<a[\s\S]*?</a>[\s]*</strong></p>#iu", '', $this->data['text']); //удаление "Читайте:***" и т.д.
        $this->data['text']         = preg_replace("#>[\s]*Читайте также:[\s\S]+?</a>#iu", '> </a>', $this->data['text']); //удаление Читайте также:
        
        #<video>
//        $this->data['text']         = preg_replace( "#<script[\s\S]+?addVariable\('media_id', '([\d]+)'\);[\s\S]+?</script>#iu", 
//                                                    parse_lib::comment_tags("<p style='text-align:center;'><embed src='http://ru.tsn.ua/bin/player/embed.php/$1' type='application/x-shockwave-flash' width='600' height='537' allowfullscreen='true' allowscriptaccess='always'></embed></p>"), 
//                                                    $this->data['text']); 
        #</video>
    }
    
} 

class parseKorrespondent extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('.post-item__photo img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.post-item__photo img',0)->src;
            $this->html_obj->find('.post-item__photo',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('.post-item__text',0) ) )
            $this->data['text']    = $this->html_obj->find('.post-item__text',0)->innertext;
        
        $this->data['text']         = preg_replace("#>[\s]*Читайте также:[\s\S]+?</a>#iu", '> </a>', $this->data['text']); //удаление Читайте также:
//        $this->data['text']     = iconv('utf-8', 'utf-8//IGNORE', $this->data['text']);
//        $this->data['title']    = iconv('cp1251', 'utf-8//IGNORE', $this->data['title']);
    }
}

class parseSegodnya extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('.article_cut_image img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.article_cut_image img',0)->src;
            $this->html_obj->find('.article_cut',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('h1',0) ) ){
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
            $this->data['title']    = preg_replace("#\(\s*в(и|і)део\s*\)#iu", ' ', $this->data['title']); //удаление слова "видео"
        }
        
        if( is_array( $this->html_obj->find('.article p') ) ){
            foreach( $this->html_obj->find('.article p') as $p ){
                $tmpP = $p->outertext;
                if( preg_match("#<strong>Читайте также:<br />#iu", $tmpP) ) continue;
                $this->data['text']    .= $tmpP."\n";
            }
        }
    }
}

class parseUnn extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('.b-news-full-img img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.b-news-full-img img',0)->src;
            $this->html_obj->find('.b-news-full-img',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('.b-news-holder',0) ) )
            $this->data['text']    = $this->html_obj->find('.b-news-holder',0)->innertext;
        
//        if( is_object( $this->html_obj->find('h2.title_leader',0) ) )
//            $this->data['text']    .= '<p><i>'.$this->html_obj->find('h2.title_leader',0)->innertext.'</i></p>';
//        
//        if( is_object( $this->html_obj->find('.news_inside_page p.link',0) ) )
//                $this->html_obj->find('.news_inside_page p.link',0)->outertext = '';
//        
//        if( is_array( $this->html_obj->find('.news_inside_page p') ) )
//            foreach( $this->html_obj->find('.news_inside_page p') as $p ){
//                $this->data['text']    .= $p->outertext."\n";
//            }
    }
}

class parseUnian extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('.photo_block img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.photo_block img',0)->src;
            $this->html_obj->find('.photo_block img',0)->outertext = '';
            
            if( is_object( $this->html_obj->find('.subscribe_photo_text',0) ) ){
                $this->html_obj->find('.subscribe_photo_text',0)->outertext = '';
            }
        }
        
        if( is_object( $this->html_obj->find('.read_also',0) ) )
            $this->data['text']     = $this->html_obj->find('.read_also',0)->outertext = '';
        
        if( is_object( $this->html_obj->find('.article_body',0) ) )
            $this->data['text']     = $this->html_obj->find('.article_body',0)->innertext;
        
//        $this->data['text']         = preg_replace("#<p>[\s]*По теме:[\s\S]*?</p>#iu", '', $this->data['text']);
        
    }
}

class parseNewsLiga extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('.annotation',0) ) )
            $this->data['text']    .= '<h2>'.$this->html_obj->find('.annotation',0)->innertext."</h2>\n";
        
        if( is_object( $this->html_obj->find('.img img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.img img',0)->src;
            $this->html_obj->find('.img',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('.text',0) ) )
            $this->data['text']    .= $this->html_obj->find('.text',0)->innertext;
        
        $this->data['text']         = preg_replace("#<b>Подписывайтесь на аккаунт[\s\S]+?</b>#iu", '', $this->data['text']); //удаление "Подписывайтесь***" и т.д.
    }
}

class parseInterfax extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h3.article-content-title',0) ) )
            $this->data['title']    = $this->html_obj->find('h3.article-content-title',0)->innertext;
        
        if( is_object( $this->html_obj->find('img.article-content-image',0) ) ){
            $this->data['img']     .= $this->html_obj->find('img.article-content-image',0)->src;
        }
        
        if( is_object( $this->html_obj->find('div.article-content',0) ) ){
            $this->data['text']    .= $this->html_obj->find('div.article-content',0)->innertext;
            $this->data['text']    = nl2br( $this->data['text'] );
        }
    }
}

class parseDelo extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('h2.art_teaser',0) ) )
            $this->data['text']    .= '<h2>'.$this->html_obj->find('h2.art_teaser',0)->innertext."</h2>\n";
        
        if( is_object( $this->html_obj->find('.big-img img',0) ) ){
            $this->data['text']    .= $this->html_obj->find('.big-img img',0)->outertext."\n";
            $this->data['img']      = $this->html_obj->find('.big-img img',0)->src;
        }
        
        if( is_object( $this->html_obj->find('#hypercontext',0) ) )
            $this->data['text']    .= $this->html_obj->find('#hypercontext',0)->innertext;
    }
}

class parseFocus extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('.single h1 #pedit',0) ) )
            $this->html_obj->find('.single h1 #pedit',0)->outertext = '';
        
        if( is_object( $this->html_obj->find('.single h1',0) ) )
            $this->data['title']    = $this->html_obj->find('.single h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('.subheader',0) ) )
            $this->data['text']    .= '<h2>'.$this->html_obj->find('.subheader',0)->innertext."</h2>\n";
        
        if( is_object( $this->html_obj->find('.single-pic img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.single-pic img',0)->src;
            $this->html_obj->find('.single-pic',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('#dcontent',0) ) )
            $this->data['text']    .= $this->html_obj->find('#dcontent',0)->innertext;
        
        if( stripos( $this->data['title'], 'Главное за день') !== false )
            $this->data['title']    = '';
    }
}

class parseIsport extends parse_page{
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1',0) ) )
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
        
        if( is_object( $this->html_obj->find('#ctl00_News3_c1_5_intro',0) ) )
            $this->data['text']    .= '<h2>'.$this->html_obj->find('#ctl00_News3_c1_5_intro',0)->innertext."</h2>\n";
        
        if( is_object( $this->html_obj->find('#ctl00_News3_c1_5_image',0) ) ){
            $this->data['text']    .= $this->html_obj->find('#ctl00_News3_c1_5_image',0)->outertext."\n";
            $this->data['img']      = $this->html_obj->find('#ctl00_News3_c1_5_image',0)->src;
        }
        
        if( is_object( $this->html_obj->find('#ctl00_News3_c1_5_FullText',0) ) )
            $this->data['text']    .= $this->html_obj->find('#ctl00_News3_c1_5_FullText',0)->innertext;
    }
}

class parseCompulenta extends parse_page{
    
    function predParseHTML( $html ){
        return parse_lib::validHTML($html);
    }
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1.article-title',0) ) )
            $this->data['title']    = $this->html_obj->find('h1.article-title',0)->innertext;
        
        
        if( is_object( $this->html_obj->find('.image-anons',0) ) ){
            foreach( $this->html_obj->find('.image-anons') as $imgAnons ){
                $imgAnons->outertext = '<br /><i style="font-size: 11px;">'.$imgAnons->innertext.'</i><br />';
            }
        }
        
        if( is_object( $this->html_obj->find('.article-lead',0) ) )
            $this->data['text']    .= '<p><i>'.$this->html_obj->find('.article-lead',0)->innertext.'</i></p>';
        
        if( is_object( $this->html_obj->find('.article-text',0) ) )
            $this->data['text']    .= $this->html_obj->find('.article-text',0)->innertext;
        
        if( is_object( $this->html_obj->find('.article-info-author',0) ) )
            $this->data['text']    .= '<p><i>Автор: '.$this->html_obj->find('.article-info-author',0)->innertext.'</i></p>';
        
//        if( is_object( $this->html_obj->find('.article-info-date',0) ) ){
//            $this->data['date'] = $this->getDate( $this->html_obj->find('.article-info-date',0)->innertext );
//        }
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#(\d{2})\s+([а-яёА-ЯЁ]+)\s+(\d{4})#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            $date = $matches[3].'-'.$this->getNbrMonthFromStr( $matches[2] ).'-'.$matches[1].' '.rand(10,22).':00:00';
        }
        
        return $date;
    }
}

class parseItc extends parse_page{
    
    function predParseHTML($html) {
        
        $imgURL = $this->donorData['main_img_url'];
        if( empty($imgURL) ) return $html;
        
        $pattern    = "#<img[\s\S]+?src=['\"]{$imgURL}['\"][\s\S]+?>#iu";
//        $html       = preg_replace($pattern, '', $html);
        
        return $html;
    }
    
    function parseDOM(){
        if( is_object( $this->html_obj->find('h1.post-title',0) ) ){
            $this->data['title']    = $this->html_obj->find('h1.post-title',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('.article-content',0) ) ){
            
            
            $this->cleaner->delSingle('.article-content .post-tags', 0);
            $this->cleaner->delSingle('.article-content .social', 0);
            $this->cleaner->delSingle('.article-content .post-types', 0);
            $this->cleaner->delSingle('.article-content .itc-hl', 0);
            $this->cleaner->delSingle('.article-content .itc-share', 0);
            $this->cleaner->delSingle('.article-content .za-protiv', 0);
            $this->cleaner->delSingle('.article-content .competitors', 0);
            
            $this->cleaner->delAll('.hlinker');
            $this->cleaner->delAll('.sk-hl-00');
            $this->cleaner->delAll('td.hotlinetable-lefttop');
            $this->cleaner->delAll('td.hotlinetable-righttop');
            $this->cleaner->delAll('.spec1 h1');
            
            

            
            if( is_array( $this->html_obj->find('.fotorama--wp') ) ){ //image slider
                foreach( $this->html_obj->find('.fotorama--wp') as $fotoramaWP ){
                    $images = '';
                    if( is_object($fotoramaWP->find('a',0)) ){                        
                            $imgURL = $fotoramaWP->find('a',0)->href;
                            if( !empty($imgURL) )
                                $images .= '<p><img src="'.$imgURL.'" rel="my-fotorama--wp" /></p>';
                        }
                    $fotoramaWP->outertext = $images;
                } 
            }
            
            if( is_array( $this->html_obj->find('.fotorama') ) ){ //image slider
                foreach( $this->html_obj->find('.fotorama') as $fotorama ){
                    $images = '';
                    if( is_object($fotorama->find('img',0)) ){                        
                            $imgURL = $fotorama->find('img',0)->src;
                            if( !empty($imgURL) )
                                $images .= '<p><img src="'.$imgURL.'" rel="my-fotorama" /></p>';
                        }
                    $fotorama->outertext = $images;
                } 
            }
            
            $this->data['text'] = $this->html_obj->find('.article-content',0)->innertext;
            
            if( is_object($this->html_obj->find('.avtor a',0)) ){
                $author = $this->html_obj->find('.avtor a',0)->innertext;
                $this->data['text'] .= '<p><i>Автор: '.$author.'</i></p>';
                
                if( stripos($author, 'Реклама') ){ $this->data['text'] = ''; }
            }
            
            //del first img without text
            $firstImgPattern = "#^[^а-яёА-ЯЁ]+?<img\s*[\s\S]+?>#iu";
            while( preg_match($firstImgPattern, $this->data['text']) ){
                $this->data['text'] = preg_replace($firstImgPattern, '', $this->data['text']);
            }
            
//            if( is_object($this->html_obj->find('time[pubdate]',0)) ){
//                $this->data['date'] = $this->getDate( $this->html_obj->find('time[pubdate]',0)->pubdate );
//            }
            
//            $this->data['text'] = $this->chengeH1( $this->data['text'] );
        }
    }
    
    private function chengeH1( $html ){
        $pattern[]      = "#<h1#iu";
        $pattern[]      = "#h1>#iu";
        $replacement[]  = '<h3';
        $replacement[]  = 'h3>';
        
        $html   = preg_replace($pattern, $replacement, $html);
        
        return $html;
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#\d{4}-\d{2}-\d{2}#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            $date = $matches[0].' '.rand(10,22).':00:00';
        }
        
        return $date;
    }
}

class parseHabr extends parse_page{
    
    function parseDOM() {
        
        $this->cleaner->delSingle('.polling', 0);
        
        if( is_object( $this->html_obj->find('h1.title span.post_title',0) ) ){
            $this->data['title']    = $this->html_obj->find('h1.title span.post_title',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('.content',0) ) ){
            $this->data['text'] = $this->html_obj->find('.content',0)->innertext;
            
            $firstImgPattern = "#^[^а-яёА-ЯЁ]+?<img\s*[\s\S]+?>#iu";
            while( preg_match($firstImgPattern, $this->data['text']) ){
                $this->data['text'] = preg_replace($firstImgPattern, '', $this->data['text']);
            }
        }
        
//        if( is_object( $this->html_obj->find('.published',0) ) ){
//            $this->data['date'] = $this->getDate( $this->html_obj->find('.published',0)->innertext );
//        }
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#(\d{1,2})\s+([а-яёА-ЯЁ]+)\s+(\d{4}|)#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            
            $day = $matches[1];
            if( $day < 10 ) $day = '0'.$day;
            
            $month = $this->getNbrMonthFromStr($matches[2]);
            
            $year = $matches[3];
            if( empty($year) ) $year = date("Y");
            
            $date = $year.'-'.$month.'-'.$day.' '.rand(10,22).':00:00';
        }
        
        return $date;
    }
}

class parse4PDA extends parse_page{
    
    function predParseHTML( $html ){
        return iconv('cp1251', 'utf-8//IGNORE', $html );
    }
    
    function parseDOM() {
        if( is_object( $this->html_obj->find('.product-detail .description h1',0) ) ){
            $this->data['title']    = $this->html_obj->find('.product-detail .description h1',0)->innertext;
        }
        
        $this->cleaner->delAll('.table4site');
        
        if( is_object( $this->html_obj->find('.content .content-box',0) ) ){
            
            $content = $this->html_obj->find('.content .content-box',0);
            
            #<lightbox big photo>
            if( is_array($content->find('a[data-lightbox]')) ){
                foreach( $content->find('a[data-lightbox]') as $lightbox ){
                    $bigImgUrl          = $lightbox->href;
                    if( is_object($lightbox->find('img',0)) ){
                        $defaultImgWidth    = $lightbox->find('img',0)->width;
                        if( isset($defaultImgWidth) && $defaultImgWidth < 250 ){
                            $lightbox->innertext = '<p><img src="'.$bigImgUrl.'" /></p>';
                        }
                    }
                }
            }
            #</lightbox big photo>
            
            $this->data['text'] = $content->innertext;
//            $this->data['text'] = $this->html_obj->find('.content .content-box',0)->innertext;
            
//            if( is_object( $this->html_obj->find('.info-holder .date',0) ) ){
//                $this->data['date'] = $this->getDate( $this->html_obj->find('.info-holder .date',0)->innertext );
//            }
        }
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#(\d{1,2})\.(\d{1,2})\.(\d{2})#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            
            $day = $matches[1];
            if( strlen($day) < 2 ) $day = '0'.$day;
            
            $month = $matches[2];
            if( strlen($month) < 2 ) $month = '0'.$month;
            
            $year = '20'.$matches[3];
            
            $date = $year.'-'.$month.'-'.$day.' '.rand(10,22).':00:00';
        }
        
        return $date;
    }
}

class parseComputerra extends parse_page{
    
    function parseDOM() {
        
        if( is_object( $this->html_obj->find('.article h1.title',0) ) ){
            $this->data['title']    = $this->html_obj->find('.article h1.title',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('.article .author .user__name',0) ) ){
            $author = $this->html_obj->find('.article .author .user__name',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('.article',0) ) ){
            $article = $this->html_obj->find('.article',0)->innertext;
            $pattern = "#<!-- start -->[\s\S]+?<!-- fin -->#iu";
            
            preg_match($pattern, $article, $matches);
            $this->data['text'] = $matches[0];
            
            if( isset($author) ){
                $this->data['text'] .= '<p><i> Автор: '.$author.'</i></p>';
            }
        }
    }
}

class parseSupreme extends parse_page{
    
    function parseDOM() {
        
        if( is_object( $this->html_obj->find('.newszagp h1',0) ) ){
            $this->cleaner->delSingle('.newszagp h1 img', 0)->outertext = '';
            $this->data['title']    = $this->html_obj->find('.newszagp h1',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('#newsbody',0) ) ){
            $this->data['text']    = $this->html_obj->find('#newsbody',0)->innertext;
            
            if( is_object( $this->html_obj->find('#newsman',0) ) ){
                $author = $this->html_obj->find('#newsman',0)->innertext;
                
                $this->data['text'] .= '<p><i>'.$author.'</i></p>';
            }
        }
        
//        if( is_object( $this->html_obj->find('#newstime',0) ) ){
//            $this->data['date'] = $this->getDate( $this->html_obj->find('#newstime',0)->innertext );
//        }
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#(\d{1,2})\.(\d{1,2})\.(\d{4})#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            
            $day = $matches[1];
            if( strlen($day) < 2 ) $day = '0'.$day;
            
            $month = $matches[2];
            if( strlen($month) < 2 ) $month = '0'.$month;
            
            $year = $matches[3];
            
            $date = $year.'-'.$month.'-'.$day.' '.rand(10,22).':00:00';
        }
        
        return $date;
    }
}

class parseHochu extends parse_page{
    
    function parseDOM() {
        
        if( is_object( $this->html_obj->find('.maintext h1',0) ) ){
            $this->data['title']    = $this->html_obj->find('.maintext h1',0)->innertext;
        }
        
        $this->cleaner->delAll('#block_similar');
        $this->cleaner->delAll('.like_bar');
        $this->cleaner->delSingle('.follow_us', 0);
        
//        if( is_object($this->html_obj->find('.scrollable-photo-slide .items .one-photo .photo_link img',0) ) ){
//            $this->sliderImgReplace();
//        }
        
        if( is_object( $this->html_obj->find('.article-content',0) ) ){
            $this->data['text']     = $this->html_obj->find('.article-content',0)->innertext;
            $this->data['text']     = preg_replace("#<em>Следите за нашими новостями в соцсетях[\s\S]*?</em>#iu", '', $this->data['text']);
        }
        
        if( is_object( $this->html_obj->find('span.date',0) ) ){
            $this->data['date'] = $this->getDate( $this->html_obj->find('span.date',0)->innertext );
        }
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#(\d{1,2})\.(\d{1,2})\.(\d{4})#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            
            $day = $matches[1];
            if( strlen($day) < 2 ) $day = '0'.$day;
            
            $month = $matches[2];
            if( strlen($month) < 2 ) $month = '0'.$month;
            
            $year = $matches[3];
            
            $date = $year.'-'.$month.'-'.$day.' '.rand(10,22).':00:00';
            
            $date = date("Y-m-d H:i:s", strtotime( "-2 day", strtotime($date) ) );
        }
        
        return $date;
    }
    
    private function sliderImgReplace( ){
        $imgList = $this->html_obj->find('.scrollable-photo-slide .items .one-photo .photo_link img');
        
        foreach($imgList as $imgObj){
            $smallImgSrc    = $imgObj->src;
            $bigImgSrc      = str_ireplace('cropr_102x102', 'cropm_568x568', $smallImgSrc);
            $imgObj->src    = $bigImgSrc;
            $imgObj->slider = 'slider';
        }
    }
}

class parseGoodhous extends parse_page{
    
    function predParseHTML( $html ){
        return iconv('cp1251', 'utf-8//IGNORE', $html );
    }
    
    function parseDOM() {
        
        if( is_object($this->html_obj->find('.center-col .b-paging',0)) ) return false; //разбивка на несколько страниц
        
        if( is_object( $this->html_obj->find('.center-col .header-holder h1',0) ) ){
            $this->data['title']    = $this->html_obj->find('.center-col .header-holder h1',0)->innertext;
        }
        
        if( is_object($this->html_obj->find('.b-article-text .hgallery, .b-article-text .vgallery',0) ) ){
            $this->sliderImgReplace();
        }
        
        
//        $this->data['date'] = $this->getDate( $this->html_obj->find('.b-article-text',0)->innertext );
        $this->cleaner->delAll('.b-article-text .b-article-text-autor');
        
        if( is_object( $this->html_obj->find('.b-article',0) ) ){
            if( is_object( $this->html_obj->find('.header-holder .lead',0) ) ){
                $this->data['text'] = '<h2>'.$this->html_obj->find('.header-holder .lead',0)->innertext.'</h2>';
            }
            $this->data['text']    .= $this->html_obj->find('.b-article .b-article-text',0)->innertext;
        }
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#<p class=\"b-article-text-autor\">[\s\S]*?(\d{2})\s+([а-яёА-ЯЁ]+)\s+(20\d{2})#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            $date = $matches[3].'-'.$this->getNbrMonthFromStr( $matches[2] ).'-'.$matches[1].' '.rand(10,22).':00:00';
        }
        
        $date = date("Y-m-d H:i:s", strtotime( "-2 day", strtotime($date) ) );
        
        return $date;
    }
    
    private function sliderImgReplace( ){
        $galleryList = $this->html_obj->find('.b-article-text .hgallery, .b-article-text .vgallery');
        
        foreach($galleryList as $galleryObj){
            
            $imgLinkList = $galleryObj->find('.small-holder li a');
            $newHtml = '';
            if( count($imgLinkList) > 0 ){
                foreach($imgLinkList as $imgLink){
                    $title      = $imgLink->title;
                    $url        = $imgLink->href;
                    $newHtml   .= '<img src="'.$url.'" alt="'.$title.'" slider="slider" />'."\n";
                }
            }
            
            $galleryObj->outertext = '<p>'.$newHtml.'</p>'."\n\n";
        }
    }
}

class parseLadyTsnUa extends parse_page{
        
    function parseDOM() {
        
        if( is_object( $this->html_obj->find('.box_content .block_news h1.title',0) ) ){
            $this->data['title']    = $this->html_obj->find('.box_content .block_news h1.title',0)->innertext;
        }
        
        $sliderImg = '';
        if( is_object($this->html_obj->find('.box_content .block_news .scrollpane_gallery',0) ) ){
            $sliderImg = $this->sliderImgReplace();
        }

        
        if( is_object( $this->html_obj->find('.box_content .block_news',0) ) ){
            
            $this->cleaner->delAll('.box_content .picture .info'); //подпись к фото
            
            if( is_object( $this->html_obj->find('.box_content .block_news .news_content .news_text .picture a',0) ) ){
                $this->data['img']  = $this->html_obj->find('.box_content .block_news .news_content .news_text .picture a',0)->href;
                $this->cleaner->delSingle('.box_content .block_news .news_content .news_text .picture',0);
            }
            
            if( is_object( $this->html_obj->find('.box_content .block_news .news_text .quote',0) ) ){ //цитаты
                $quote = $this->html_obj->find('.box_content .block_news .news_text .quote');
                
                foreach($quote as $quoteObj){
                    $HtmlText = $quoteObj->innertext; 
                    $quoteObj->outertext = "\n".'<p style="text-align:center;"><i> " '.$HtmlText.' " </i></p>'."\n";
                }
            }
            
            $this->data['text'] = '';
            
            if( is_object( $this->html_obj->find('.box_content .block_news h2.descr',0) ) ){
                $this->data['text'] .= '<h2>'.$this->html_obj->find('.box_content .block_news h2.descr',0)->innertext.'</h2>';
            }
            
            $this->data['text']    .= $sliderImg;
            $this->data['text']    .= $this->html_obj->find('.box_content .block_news .news_content .news_text ',0)->innertext;
        }
        
        $this->data['date'] = $this->getDate( $this->html_obj->find('.info_top .date',0)->innertext );
    }
    
    private function getDate( $dateStr ){
        $date = false;
        $pattern = "#(\d{1,2})\s+([а-яёА-ЯЁ]+)\s+(20\d{2})#iu";
        if( preg_match($pattern, $dateStr, $matches) ){
//            echo '<pre>'.print_r($matches,1).'</pre>';
            if( strlen($matches[1]) < 2 ) $matches[1] = '0'.$matches[1];
            $date = $matches[3].'-'.$this->getNbrMonthFromStr( $matches[2] ).'-'.$matches[1].' '.rand(10,22).':00:00';
        }
        else{
            $date = date("Y-m-d H:i:s");
        }
        
        $date = date("Y-m-d H:i:s", strtotime( "-2 day", strtotime($date) ) );
        
        return $date;
    }
    
    private function sliderImgReplace( ){
        $galleryList = $this->html_obj->find('.box_content .block_news .scrollpane_gallery');
        
        $allImgHtml = '';
        
        foreach($galleryList as $galleryObj){
            
            $imgLinkList = $galleryObj->find('li.item a');
            $newHtml = '';
            if( count($imgLinkList) > 0 ){
                foreach($imgLinkList as $imgLink){
                    $title      = $imgLink->title;
                    $url        = $imgLink->href;
                    $newHtml   .= '<img src="'.$url.'" alt="'.$title.'" slider="slider" />'."\n";
                }
            }
            
//            $galleryObj->outertext = '<p>'.$newHtml.'</p>'."\n\n";
            
            $allImgHtml .= '<p>'.$newHtml.'</p>'."\n\n";
        }
        
        return $allImgHtml;
    }
}

class parseWomensHealthMag extends parse_page{
    
    function parseDOM() {
        
        if( is_object( $this->html_obj->find('.mid-content-mod h2',0) ) ){
            $this->data['title']    = $this->html_obj->find('.mid-content-mod h2',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('.article-image img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.article-image img',0)->src;
            $this->cleaner->delSingle('.article-image', 0);
        }
        
        $this->data['text'] = '';
        
        if( is_object( $this->html_obj->find('.mid-content-mod h3.tagline',0) ) ){
            $this->data['text']    .= '<h2>'.$this->html_obj->find('.mid-content-mod h3.tagline',0)->innertext.'</h2>';
        }
        
        if( is_object( $this->html_obj->find('.article-content',0) ) ){
            $this->data['text']    .= $this->html_obj->find('.article-content',0)->innertext;
            
            $pattern = "/<p>[<>bistrong]*?(MORE|RELATED):[\s\S]*?<\/p>/i";
            $this->data['text'] = preg_replace($pattern, '', $this->data['text']);
            
            $pattern = "/<p><strong>More from[\s\S]*?:[\s\S]*?<\/p>/i";
            $this->data['text'] = preg_replace($pattern, '', $this->data['text']);
        }
    }
}

class parseMsn extends parse_page{
    
    function parseDOM() {
        
        if( is_object( $this->html_obj->find('.collection-headline h1',0) ) ){
            $this->data['title'] = $this->html_obj->find('.collection-headline h1',0)->innertext;
        }
        elseif(is_object( $this->html_obj->find('.collection-headline-flex h1',0) )){ //new page stile
            $this->data['title'] = $this->html_obj->find('.collection-headline-flex h1',0)->innertext;
        }
        
        if( is_object( $this->html_obj->find('section.articlebody',0) ) ){
            $textObj = $this->html_obj->find('section.articlebody',0);
            $textObj = $this->changeVideo($textObj);
            $textObj = $this->imgInTxt($textObj);
            $textObj = $this->slideerRewrite($textObj);
            $textObj = $this->delTagFromObj($textObj);
            $textObj = $this->changeLink($textObj);
            $htmlTxt = $textObj->innertext;
            $htmlTxt = $this->delHtmlTagData($htmlTxt);
            $htmlTxt = $this->delAttrFromHtml($htmlTxt);
            $htmlTxt = $this->delTagFromHtml($htmlTxt);
            $htmlTxt = preg_replace("#<(/|)h1#iu", "<$1h2", $htmlTxt); // h1 to h2
            $htmlTxt = $this->addLikeMarker($htmlTxt, 500);
            $htmlTxt = iconv('utf-8', 'utf-8//IGNORE', $htmlTxt ); //исправление некорректных символов
            $this->data['text'] = '<div data-parse-version="1" class="parse-version p-version-1">'.$htmlTxt.'</div>';
        }
        
        #<DonorData>
        if( is_object( $this->html_obj->find('.partnerlogo-img img',0) ) )
        {
            $imgJson    = $this->html_obj->find('.partnerlogo-img img',0)->attr['data-src'];
            
            $imgJson    = html_entity_decode($imgJson);
            $imgAr      = json_decode($imgJson, true);
            $imgSrc     = 'http:'.$imgAr['default'];

            $searchAr   = array("#h=\d{2,3}#iu","#w=\d{2,3}#iu","#q=\d{1,2}#iu");
            $replaceAr  = array("h=32","w=32","q=100");
            $imgSrc     = preg_replace($searchAr, $replaceAr, $imgSrc);
            
            $this->data['donor-data']['img'] = $imgSrc;
        }
        
        if( is_object( $this->html_obj->find('.sourcename-txt a',0) ) )
        {
            $this->data['donor-data']['name'] = $this->html_obj->find('.sourcename-txt a',0)->innertext;
            
            $donorUrl   = $this->html_obj->find('.sourcename-txt a',0)->href;
            $donorUrlAr = parse_url($donorUrl);
            
            $this->data['donor-data']['host'] = trim($donorUrlAr['host']);
        }
        elseif ( is_object($this->html_obj->find('.partnerlogo-img a',0)) ) { // ===== new page style =====
            $this->data['donor-data']['name'] = $this->html_obj->find('.partnerlogo-img a',0)->title;
            
            $donorUrl   = $this->html_obj->find('.partnerlogo-img a',0)->href;
            $donorUrlAr = parse_url($donorUrl);
            
            $this->data['donor-data']['host'] = trim($donorUrlAr['host']);
        }
        else
        {   
            if( is_object($this->html_obj->find('.sourcename-txt',0)) ){ //получение хоста по имени из массива сохраненных
                $donorName      = trim($this->html_obj->find('.sourcename-txt',0)->innertext);
                echo "<br />\n--Text Source--".$donorName."--/Text Source--\n<br />";
                $donorInfoAr    = get_donor_info_by_name($donorName);
                
                if(is_array($donorInfoAr))
                {
                    $this->data['donor-data']['name'] = $donorName;
                    $this->data['donor-data']['host'] = $donorInfoAr['host'];
                }
            }
            
            if(isset($donorInfoAr) == false OR $donorInfoAr == false)
            {
                $this->data['donor-data']['host'] = 'www.msn.com';
                $this->data['donor-data']['name'] = 'MSN';
                if( is_object( $this->html_obj->find('link[rel=shortcut icon]',0) ) ){
                    $this->data['donor-data']['img'] = 'http:'.$this->html_obj->find('link[rel=shortcut icon]',0)->href;
                }
            }
        }
        
        if( is_object( $this->html_obj->find('meta[name=description]',0) ) ){
            $description = $this->html_obj->find('meta[name=description]',0)->content;
            $this->data['description'] = $this->getBigDescription($description, $this->data['text'], 600, 1500);
            #echo "\n\n<br />------<br />\n".$this->data['description']."\n<br />------<br />\n\n";
        }
        
        if( is_object( $this->html_obj->find('link[rel=canonical]',0) ) ){
            $this->data['canonical'] = $this->html_obj->find('link[rel=canonical]',0)->href;
        }
        #</DonorData>
    }
    
    private function changeVideo($textObj){
        if( !is_object($textObj->find('.wcvideoplayer',0)) )
        {
            return $textObj;
        }
        
        foreach($textObj->find('.wcvideoplayer') as $videoObj)
        {
            $metaData   = $videoObj->attr['data-metadata'];
            $metaData   = html_entity_decode($metaData);
            $metaDataAr = json_decode($metaData,true);
//            print_r($metaDataAr);
            
            $htmlVideo = '<video width="100%" height="auto"  poster="'.$metaDataAr['headlineImage']['url'].'" controls > '
                    . '<source src="'.$metaDataAr['videoFiles'][0]['url'].'" > '
                    . 'Your browser does not support this video'
                    . '</video>';
            
            $videoObj->outertext = $htmlVideo;
        }
        
        return $textObj;
    }


    private function imgInTxt($textObj){
        if( !is_object($textObj->find('img',0)) )
        {
            return $textObj;
        }
        
        foreach($textObj->find('img') as $imgObj)
        {
            if(!isset($imgObj->attr['data-src']))
            {
                continue;
            }
            
            $imgJson    = $imgObj->attr['data-src'];
            $imgJson    = html_entity_decode($imgJson);
            $imgAr      = json_decode($imgJson, true);
            if(is_array($imgAr['default']))
            {
                $imgSrc     = 'http:'.$imgAr['default']['src'];
            }
            else
            {
                $imgSrc     = 'http:'.$imgAr['default'];
            }
            
            //<MaxImgSize>
            preg_match("#w=(\d+)#iu", $imgSrc, $matches);
            if(isset($matches[1]) && $matches[1] > 616)
            {
                $searchAr   = array("#h=\d{2,4}#iu","#w=\d{2,4}#iu","#q=\d{1,2}#iu");
                $replaceAr  = array("h=","w=616");
                $imgSrc     = preg_replace($searchAr, $replaceAr, $imgSrc);
            }
            //</MaxImgSize>
            
            $imgSrc = preg_replace("#q=\d{1,2}#iu", "q=100", $imgSrc); // quality 100%
            
            $imgObj->attr['src'] = $imgSrc;
            
            if( !isset($imgObj->attr['alt']) || empty($imgObj->attr['alt']) )
            {
                $imgObj->attr['alt'] = $this->data['title'];
            }
            unset($imgObj->attr['data-src']);
        }
        
        return $textObj;
    } 
    
    private function slideerRewrite($textObj){
        if( !is_object($textObj->find('.inline-slideshow',0)) )
        {
            return $textObj;
        }
        
        foreach($textObj->find('.inline-slideshow') as $sliderObj)
        {
            $i=0;
            foreach($sliderObj->find('ul.slideshow li') as $slideLi)
            {
                $slideTxtData  = $sliderObj->find('.gallerydata div.slidemetadata-container',$i)->outertext;
                $slideTxtData .= $sliderObj->find('.gallerydata div.body-text',$i)->outertext;
                
                $slideLi->innertext = $slideLi->innertext."\n".$slideTxtData;
                $i++;
            }
            $sliderObj->find('.gallerydata',0)->outertext = '';
        }
        
        return $textObj;
    }
    
    private function delTagFromObj($textObj){
        $cleaner  = new cleanDOM($textObj);
        
        $cleaner->delAll('div.thumbnail-container'); //del fullScreen btn in photoSlider
        $cleaner->delAllWrapper('div.arsegment'); //del virtual page tag
        
        $cleaner->delAll('#findacar'); // del auto search block 
        $cleaner->delAll('button'); // del all <button>
        $cleaner->delAll('div.ec-module'); //msn <iframe> in div.ec-module
        
        //<video player content>
        $cleaner->delAll('div.metadata'); //del video info
        $cleaner->delAll('div.playlist-and-storepromo'); //del video info
        $cleaner->delAll('div.nextvideo-outer'); //del video info
        //<video player content>
        
        
        //<image slider follow>
        
        //</image slider follow>
        
        
        return $textObj;
    }
    
    private function delHtmlTagData($html){
        $pattern = "#data-[\w-]+\s*=\s*\"[\s\S]*?\"#iu";
        
        $cleanHtml = preg_replace($pattern, '', $html);
        
        return $cleanHtml;
    }
    
    private function delTagFromHtml($html){
        
        $pattern = "#</?(figure|figcaption)[\S\s]*?>#iu";
        $newHtml = preg_replace($pattern, '', $html);
        
        $patternP = "#<p>\s*</p>#iu";
        $newHtml = preg_replace($patternP, '', $newHtml);
        
        return $newHtml;
    }
    
    private function delAttrFromHtml($html){
        
        $patternXmlns   = "#xmlns=\"http://www.w3.org/1999/xhtml\"#iu";
        $newHtml        = preg_replace($patternXmlns, '', $html);
        
        $patternSpace   = "#\s+>#iu"; //space in tag 
        $newHtml        = preg_replace($patternSpace, '>', $newHtml);
        
        $patternSpace2  = "#>\s+#iu"; //space in tag 
        $newHtml        = preg_replace($patternSpace2, '> ', $newHtml);
        
        $patternSpace3  = "#\s+<#iu"; //space in tag 
        $newHtml        = preg_replace($patternSpace3, ' <', $newHtml);
        
        return $newHtml;
    }
    
    private function getBigDescription($descripion, $txtHtml, $minLenth=300, $maxLenth = 1500){
        
        $descripion = str_ireplace('...', '', $descripion);
        
        $descLenth = $this->txtLenth($descripion);
        if($descLenth > $minLenth){
            if($descLenth > $maxLenth)
            {
                $descripion = $this->get_short_txt($descripion, $maxLenth, 'dot');
                #echo "\n\n<br />Max Lenth - ".$maxLenth."<br />\n\n";
            }
            $descripion  = preg_replace("#\.[^\.]+$#iu", '.', $descripion);
            return $descripion;
        }
        
        $intPlusDesc = $minLenth - $descLenth + 30; //30 - deleted search str

        $text       = strip_tags($txtHtml);
        
        $searchDescriptSrt = $this->getSearchDescription($descripion);
        if($searchDescriptSrt)
        {
            $pos        = mb_stripos($text, $searchDescriptSrt);
            if($pos!==false)
            {
                $descPlus   = mb_substr($text, $pos, $intPlusDesc);
                $descPlus   = str_ireplace($searchDescriptSrt, ' ', $descPlus);
                $descPlus   = preg_replace("#\.[^\.]+$#iu", '.', $descPlus);
                
                $descripion .=  $descPlus;
            }
            elseif(is_object($this->html_obj->find('section.articlebody',0))){ //add text in <p/> to description
                $articleObj = $this->html_obj->find('section.articlebody',0);
                if(is_object($articleObj->find('span.storyimage',0)))
                {
                    $articleObj->find('span.storyimage',0)->outertext = '';
                }
                if(is_object($articleObj->find('p',1)))
                {
                    $pTxt   = $articleObj->find('p',1)->innertext;
                    $pTxt   = strip_tags($pTxt);
                    $descPlus   = mb_substr($pTxt, 0, $intPlusDesc+30); //+30 - deleted search text in upper function
                    $descPlus   = preg_replace("#\.[^\.]+$#iu", '.', $descPlus);
                    
                    $descripion .=  $descPlus;
                }
            }
        }
        
        return $descripion;
    }
    
    private function txtLenth($html){
        $text   = strip_tags($html);
        $text   = preg_replace("#[/,:;\!\?\(\)\.\s]#iu", '', $text);
        $lenth  = mb_strlen($text);
        
        return $lenth;
    }
    
    private function getSearchDescription($descripion){
        if(preg_match("#.{30}$#iu", $descripion, $arr))
        {
            return $arr[0];
        }
        else
        {
            return false;
        }
    }
    
    private function changeLink($textObj){
        if(is_object($textObj->find('a',0)) == false){
            return $textObj;
        }
        
        foreach($textObj->find('a') as $linkObj){
            $anchor = $linkObj->innertext;
            $href   = $linkObj->href;
            
            $spanLink = '<span class="out-link" src="'.$href.'">'.$anchor.'</span>';
            
            $linkObj->outertext = $spanLink;
        }
        
        return $textObj;
    }
    
    private function addLikeMarker($htmlTxt, $afterCntSimbol = 500){
        $startAfterCntSimbol = $afterCntSimbol;
        $marker     = '<!--likeMarker-->'; //"\n".'<h1>likeMarker</h1>'."\n";
        $tmpHtml    = $htmlTxt;
        $pArr       = explode('</p>', $tmpHtml);
        
        if(count($pArr)<1){ return $htmlTxt; /*######*/ }
        
        $cntSimbol = 0;
        foreach($pArr as $pStr)
        {
            $pStr .= '</p>';
            $pLenth = $this->txtLenth($pStr);
            
            $cntSimbol = $cntSimbol + $pLenth;
            
            if($cntSimbol >= $afterCntSimbol){
                $afterCntSimbol = round($afterCntSimbol * 1.2);
                $cntSimbol = 0;
                preg_match("#.{30}$#iu", $pStr, $matches);
                
                $searchStr  = $matches[0];
                $replaceStr = $searchStr.$marker;
                $replaceStr = str_ireplace('</p>', '<!--#--></p>', $replaceStr); //уникализация строки для исключения дублирования замены
                
//                echo $searchStr."<br />\n";
                
                $htmlTxt = str_ireplace($searchStr, $replaceStr, $htmlTxt);
            }
        }
        
        $lastCntTxt = round($startAfterCntSimbol * 0.7);
        
        $htmlTxt = preg_replace("#{$marker}(.{0,{$afterCntSimbol}})$#iu", "$1", $htmlTxt);
        $htmlTxt = $marker.$htmlTxt;
        return $htmlTxt;
    }
    
    private function get_short_txt( $text, $length = 100, $txtFin = 'word' ){
        $text = strip_tags($text);
        $text = mb_substr($text, 0, $length);
        
        if( $txtFin == 'word' ){
            $replacePattern = "# \S+$#i";
            $replace = '';
        }
        elseif( $txtFin == 'dot' ){
            $replacePattern = "#\. [^\.]+$#i";
            $replace = '.';
        }
        
        $text = preg_replace( $replacePattern, $replace, $text );
        
        return $text;
    }
}

