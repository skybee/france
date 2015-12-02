<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ParseMethod{
    
    private $parseMethodNmae, $html;
    
    function __construct($html, $host) {
        
        $this->html = $html;
        
        switch( $host ){
            case 'tsn.ua': 
                $this->parseMethodNmae = 'tsn';           
                break;
            case 'korrespondent.net' :          
                $this->parseMethodNmae = 'korrespondent'; 
                break;
            case 'www.segodnya.ua' :            
                $this->parseMethodNmae = 'segodnya';      
                break;
            case 'www.unn.com.ua':              
                $this->parseMethodNmae = 'unn';           
                break;
            case 'www.unian.net' :              
                $this->parseMethodNmae = 'unian';         
                break;
            case 'liga.net' :                   
                $this->parseMethodNmae = 'news_liga';     
                break;
            case 'interfax.com.ua' :            
                $this->parseMethodNmae = 'interfax';      
                break;
            case 'sport.segodnya.ua' :          
                $this->parseMethodNmae = 'segodnya';      
                break;
            case 'delo.ua' :                    
                $this->parseMethodNmae = 'delo';          
                break;
            case 'focus.ua' :                   
                $this->parseMethodNmae = 'focus';         
                break;
            case 'isport.ua' :                  
                $this->parseMethodNmae = 'isport';        
                break;
            case 'compulenta.computerra.ru':    
                $this->parseMethodNmae = 'compulenta';    
                $this->html = parse_lib::validHTML($html);
                break; 
            case 'itc.ua':                      
                $this->parseMethodNmae = 'itc';           
                break;
            default: 
                $this->parseMethodNmae = false;
        }
    }
    
    function getparseMethodNmae(){
        return $this->parseMethodNmae;
    }
    
    function getHTML(){
        return $this->html;
    } 
}


class parse_page_lib{
    
    private $cleaner, $html_obj;
    
    function __construct(){}
    
    function get_data( $html, $host){
        
        $this->data = FALSE;
        
        $parse          = new parseMethod($html, $host);
        $html           = $parse->getHTML();
        $method         = $parse->getparseMethodNmae();
        
        if( !$method ) return FALSE;
        
        $this->html_obj = str_get_html($html);
        $this->cleaner  = new cleanDOM($this->html_obj);
        $this->$method();
        $this->html_obj->clear();
        
        unset( $this->html_obj );
        
        $this->data['text'] =  video_replace_lib::get_video_tags( $this->data['text'] );
        
        return $this->data;
    }
    
    private function tsn(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
                preg_match("#<div class='v_player' id='video_player'></div>[\s]+<script[\s\S]+?addVariable\('media_id', '[\d]+'\);[\s\S]+?</script>#i", $html, $videoJsArr );
                $this->data['text'] .= "\n\n".$videoJsArr[0];
            }
        }
        
//        $this->data['text']         = preg_replace("#<p><strong>[\s\S]{4,20}:[\s]*<a[\s\S]*?</a>[\s]*</strong></p>#iu", '', $this->data['text']); //удаление "Читайте:***" и т.д.
        $this->data['text']         = preg_replace("#>[\s]*Читайте также:[\s\S]+?</a>#iu", '> </a>', $this->data['text']); //удаление Читайте также:
        
        #<video>
//        $this->data['text']         = preg_replace( "#<script[\s\S]+?addVariable\('media_id', '([\d]+)'\);[\s\S]+?</script>#i", 
//                                                    parse_lib::comment_tags("<p style='text-align:center;'><embed src='http://ru.tsn.ua/bin/player/embed.php/$1' type='application/x-shockwave-flash' width='600' height='537' allowfullscreen='true' allowscriptaccess='always'></embed></p>"), 
//                                                    $this->data['text']); 
        #</video>
    } #+
    
    private function unn(){
        $this->data['text'] = '';
        $this->data['img']  = '';
                
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
    } #+ ?
    
    private function unian(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
    
    private function interfax(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
    
    private function segodnya(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
        if( is_object( $this->html_obj->find('.article_cut_image img',0) ) ){
            $this->data['img']      = $this->html_obj->find('.article_cut_image img',0)->src;
            $this->html_obj->find('.article_cut',0)->outertext = '';
        }
        
        if( is_object( $this->html_obj->find('h1',0) ) ){
            $this->data['title']    = $this->html_obj->find('h1',0)->innertext;
            $this->data['title']    = preg_replace("#\(\s*в(и|і)део\s*\)#i", ' ', $this->data['title']); //удаление слова "видео"
        }
        
        if( is_array( $this->html_obj->find('.article p') ) ){
            foreach( $this->html_obj->find('.article p') as $p ){
                $tmpP = $p->outertext;
                if( preg_match("#<strong>Читайте также:<br />#i", $tmpP) ) continue;
                $this->data['text']    .= $tmpP."\n";
            }
        }
    } #+
    
    private function delo(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
    
    private function focus(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
    
    private function news_liga(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
        
        $this->data['text']         = preg_replace("#<b>Подписывайтесь на аккаунт[\s\S]+?</b>#i", '', $this->data['text']); //удаление "Подписывайтесь***" и т.д.
    }
    
    private function isport(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
    
    private function korrespondent(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
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
    } #+
    
    private function compulenta(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        $this->data['date_str']  = '';
        
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
        
        if( is_object( $this->html_obj->find('.article-info-date',0) ) )
            $this->data['date_str'] = $this->html_obj->find('.article-info-date',0)->innertext;
    }
    
    private function itc(){
        $this->data['text'] = '';
        $this->data['img']  = '';
        
        if( is_object( $this->html_obj->find('h1.post-title',0) ) )
            $this->data['title']    = $this->html_obj->find('h1.post-title',0)->innertext;
        
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
            $firstImgPattern = "#^[^а-яёА-ЯЁ]+?<img\s*[\s\S]+?>#i";
            while( preg_match($firstImgPattern, $this->data['text']) ){
                $this->data['text'] = preg_replace($firstImgPattern, '', $this->data['text']);
            }
        }
        
//        if( is_object( $this->html_obj->find('.article-info-date',0) ) )
//            $this->data['date_str'] = $this->html_obj->find('.article-info-date',0)->innertext;
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
}
