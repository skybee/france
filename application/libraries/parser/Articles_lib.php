<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles_lib{
    
    private $scanUrl;
    
    function __construct() {}
    
    function setScanUrl( $url ){
        $this->scanUrl = $url;
    }
    
    function getData( $host ){
        if( empty($this->scanUrl) ) exit('No Url to Scan');
        
        $dataObj = $this->getParseObj( $host );
        return $dataObj->getData();
    }
    
    private function getParseObj( $host ){
        switch( $host ){
            case 'compulenta.computerra.ru':    return new parseCompulentaList( $this->scanUrl );
            case 'itc.ua':                      return new parseItcList( $this->scanUrl );
            case 'habrahabr.ru':                return new parseHabrList( $this->scanUrl ); 
            case '4pda.ru':                     return new parse4PDAList( $this->scanUrl );   
            case 'www.computerra.ru':           return new parseComputerraList( $this->scanUrl );     
            case 'supreme2.ru':                 return new parseSupreme2List( $this->scanUrl );
            case 'hochu.ua':                    return new parseHochuList( $this->scanUrl );
            case 'www.goodhouse.ru':            return new parseGoodhousList( $this->scanUrl );
            case 'lady.tsn.ua':                 return new parseLadyTsnUaList( $this->scanUrl );    
            case 'www.womenshealthmag.com':     return new parseWomensHealthMagList( $this->scanUrl );
            case 'www.msn.com':                 return new parseMsnList( $this->scanUrl );    
            default: return false;
        }
    }
}

abstract class parseArticleList{
    
    protected $html_obj, $data, $err_msg;
    
    public function __construct( $url ) {
        $this->ci       = &get_instance();
        
        $html           = $this->ci->news_parser_lib->down_with_curl( $url, false, true ); //($url, $getInfo, $useProxy)
        $this->html_obj = str_get_html($html);
        $this->data     = $this->getUrlTitleImgFomPage();
        $this->data     = $this->convertToRealUrl( $this->data, $url );
    }
    
    public function getData(){
        return $this->data;
    }
    
    protected function convertToRealUrl( $dataAr, $baseUrl ){
        
        if( $dataAr == NULL ){ return $dataAr; }
        
        foreach( $dataAr as $fstKey => $secAr ){
            foreach( $secAr as $secKey => $secVal ){
                if( !empty($secVal) )
                    $dataAr[$fstKey][$secKey] = parse_lib::uri2absolute($secVal, $baseUrl);
            }
        }
        
        return $dataAr;
    }
    
    abstract protected function getUrlTitleImgFomPage();
    
}


class parseCompulentaList extends parseArticleList{
    
    function __construct( $url ) {
        parent::__construct( $url );
    }
    
    protected function getUrlTitleImgFomPage() {
        
        if( !is_object($this->html_obj->find('.category-list-item',0) ) ) return false;
        
        $list   = &$this->html_obj->find('.category-list-item');
        $cnt    = count($list);
        $data   = array();
        
        for( $i=0; $i<$cnt; $i++){
            $block = &$list[$i]; 
            
            if( is_object( $block->find('.category-list-item-title',0) ) ){
                $data[$i]['url']    =  $block->find('.category-list-item-title',0)->href;
                $data[$i]['img']    =  $block->find('.category-list-item-img img',0)->src;
            }
                    
        }  
        return $data;
    }
}

class parseItcList extends parseArticleList{
    
    function __construct( $url ) {
        parent::__construct( $url );
    }
    
    protected function getUrlTitleImgFomPage() {
        
        if( !is_object($this->html_obj->find('article.post',0) ) ) return false;
        
//        foreach( $this->html_obj->find('.hlimage img') as $hlimage ){
//            $hlimage = NULL;
//        }
        
        
        $i=0;
        foreach( $this->html_obj->find('article.post') as $list ){
            
            $data[$i]['url']        =  $list->find('.post-title a',0)->href;
            
            if( is_object($list->find('.article-content img[width]',0)) ){
                $data[$i]['img']    =  $list->find('.article-content  img[width]',0)->src;
                
                if( preg_match("#/i/\S+\.gif#i", $data[$i]['img']) ){ //не те картинки
                    $data[$i]['img'] = '';
                }    
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
          
        return $data;
    }
}

class parseHabrList extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.post',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.post') as $list ){
            
            $data[$i]['url']        =  $list->find('.post_title',0)->href;
            
            if( is_object($list->find('.content img',0)) ){
                $data[$i]['img']    =  $list->find('.content img',0)->src;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
          
        return $data;
    }
}

class parse4PDAList extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }

    protected function getUrlTitleImgFomPage() {
        
        if( is_object($this->html_obj->find('ul.product-list',0)) ){
            return $this->scanReviews();
        }
        
        if( !is_object($this->html_obj->find('.post',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.post') as $list ){
            
            $data[$i]['url']        =  $list->find('.list-post-title a',0)->href;
            
            if( is_object($list->find('.visual img',0)) ){
                $data[$i]['img']    =  $list->find('.visual img',0)->src;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
        return $data;
    }
    
    private function scanReviews(){
        if( !is_object($this->html_obj->find('.photo',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.photo') as $list ){
            
            $data[$i]['url']        =  $list->find('a',0)->href;
            
            if( is_object($list->find('img',0)) ){
                $data[$i]['img']    =  $list->find('img',0)->src;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
        return $data;
        
    }
}

class parseComputerraList extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.item, .item-dir-ct',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.item, .item-dir-ct') as $list ){
            
            if( !is_object( $list->find('h2 a',0) ) ){ 
                continue;
            }
            
            $data[$i]['url']        =  $list->find('h2 a',0)->href;
            
            if( is_object($list->find('.item-pic img',0)) ){
                $data[$i]['img']    =  $list->find('.item-pic img',0)->src;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
          
        return $data;
    }
}

class  parseSupreme2List extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.ximpost',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.ximpost') as $list ){
            
            $data[$i]['url']        =  $list->find(' a',0)->href;
            
            if( is_object($list->find('img',0)) ){
                $data[$i]['img']    =  $list->find('img',0)->src;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
          
        return $data;
    }
}

class  parseHochuList extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.ca__item',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.ca__item') as $list ){
            
            $data[$i]['url']        =  $list->find('h2.ca__title a',0)->href;
            
            if( is_object($list->find('.ca__image img',0)) ){
                $data[$i]['img']    =  $list->find('.ca__image img',0)->src;
                $data[$i]['img']    =  preg_replace("#\.(jpg|jpeg|gif|png)\?\S+#i", ".$1", $data[$i]['img']);
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
          
        return $data;
    }
}

class parseGoodhousList extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.b-catalog',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.b-catalog a.section__item__pic') as $list ){
            $data[$i]['url']        =  $list->href;
            
            if( is_object($list->find('img',0)) ){
                $data[$i]['img']    =  $list->find('img',0)->src;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
          
        return $data;
    }
}

class parseLadyTsnUaList extends parseArticleList{
    
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.content ul.list li.item',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('.content ul.list li.item h2.title a') as $list ){
            $data[$i]['url']    =  $list->href;
            $data[$i]['img']    =  '';
            
            $i++;
        }
          
        return $data;
    }
}


class parseWomensHealthMagList extends parseArticleList{
    
    function __construct($url) {
        $this->ci       = &get_instance();
        
        $json           = $this->ci->news_parser_lib->down_with_curl( $url );
        $jsonObj        = json_decode($json);
        $html           = '<html>'.$jsonObj->html.'</html>';
        $this->html_obj = str_get_html($html);
        $this->data     = $this->getUrlTitleImgFomPage();
        $this->data     = $this->convertToRealUrl( $this->data, $url );
    }
    
    protected function getUrlTitleImgFomPage() {
        if( !is_object($this->html_obj->find('.item',0) ) ) return false;
        
        $i=0;
        foreach( $this->html_obj->find('div.item') as $list ){
            $data[$i]['url']    = $list->find('a',0)->href .'?fullpage=1';
            $data[$i]['img']    =  '';
            
            $i++;
        }
          
        return $data;
    }
}

class parseMsnList extends parseArticleList{
    function __construct($url) {
        parent::__construct($url);
    }
    
    protected function getUrlTitleImgFomPage() {
        
        return $this->secondLiParse();
        
//        if( !is_object($this->html_obj->find('.sectioncontent',0) ) ) return $this->secondLiParse();
//        
//        $i=0;
//        foreach( $this->html_obj->find('li.smalla') as $list ){
//            $data[$i]['url']    =  $list->find('a',0)->href;
//            
//            if( is_object($list->find('img',0)) ){
//                $imgJson    = $list->find('img',0)->attr['data-src'];
//                $imgJson    = html_entity_decode($imgJson);
//                $imgAr      = json_decode($imgJson, true);
//                $img        = 'http:'.$imgAr['default'];
//                
//                $searchAr   = array("#h=\d{2,3}#i","#w=\d{2,3}#i","#q=\d{1,2}#i");
//                $replaceAr  = array("h=200","w=300","q=100");
//                $img        = preg_replace($searchAr, $replaceAr, $img);
//
//                $data[$i]['img']    =  $img;
//            }        
//            else{
//                $data[$i]['img']    =  '';
//            }
//            $i++;
//        }
//          
//        return $data;
    }
    
    protected function secondLiParse(){
        
//        $this->rssParse(); return;
        
        $this->cleaner();
        
        $i=0;
        foreach( $this->html_obj->find('li[data-id]') as $list ){
            
            if( !is_object($list->find('span.sourcename',0)) ||  !is_object($list->find('a',0)) )
            {
                continue;
            }
                
            $data[$i]['url']    =  $list->find('a',0)->href;
            
            if( is_object($list->find('img',0)) ){
                $imgJson    = $list->find('img',0)->attr['data-src'];
                $imgJson    = html_entity_decode($imgJson);
                $imgAr      = json_decode($imgJson, true);
                $img        = 'http:'.$imgAr['default'];
                
                $searchAr   = array("#h=\d{2,3}#i","#w=\d{2,3}#i","#q=\d{1,2}#i");
                $replaceAr  = array("h=200","w=300","q=100");
                $img        = preg_replace($searchAr, $replaceAr, $img);
                
                if(preg_match("#\.img\?#i", $img)) //замена расширения .img
                {
                    preg_match("#f=(jpg|jpeg|png|gif)#", $img, $ar);
                    
                    if(isset($ar[1]) && !empty($ar[1]))
                    {
                        $img = preg_replace("#\.img\?#", ".{$ar[1]}?", $img);
                    }
                    else{ // experemental .img to .jpg if "f=(jpg|jpeg|png|gif)" empty
                        $img = preg_replace("#\.img\?#", ".jpg?", $img);
                    }
                }

                $data[$i]['img']    =  $img;
            }        
            else{
                $data[$i]['img']    =  '';
            }
            $i++;
        }
        
        return $data;
    }
    
    private function rssParse(){
        echo "Carabo";
    }
    
    private function cleaner(){
        
        if(is_object($this->html_obj->find('.financeheaderlistmodule6u',0))){
            foreach ($this->html_obj->find('.financeheaderlistmodule6u') as $node){
                $node->outertext = '';
            }
        }
        
        $this->html_obj->load($this->html_obj->save()); 
    }
}


