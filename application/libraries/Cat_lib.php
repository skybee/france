<?php

class Cat_lib{
    
    private $mainCat = '', $catNameAr = array() ;
    
    function __construct() {
        $this->ci = &get_instance();
    }
    
    function getCatConfig(){
        
        return $this->getExpressNewsConf();
        
        $catConf = $this->ci->config->item('category');
        
        if( isset($catConf[$this->mainCat]) ){
            return $this->selectCatConf( $catConf );
        }
        else{
            return $catConf['default'];
        }
    }
    
    private function selectCatConf( $catConf, $catIndex = 0 ){
        
        $nextCatIndex   = $catIndex + 1;
        $catName        = $this->catNameAr[ $catIndex ];
        $thisConf       = $catConf[ $catName ];
        
        if( isset( $this->catNameAr[$nextCatIndex] ) ){
         
            if( isset( $thisConf[$this->catNameAr[$nextCatIndex]] ) ){
                return $this->selectCatConf($thisConf, $nextCatIndex);
            }
            else{
                return $thisConf;
            }
        }
        else{
            return $thisConf;
        }
    }
    
    private function getExpressNewsConf()
    {
        if(is_array($this->catNameAr) AND count($this->catNameAr)>0)
        {
            $mainCatUrlName = $this->catNameAr[0]; 
            $mainCatConf    = $this->ci->category_m->getCatTimeSet($mainCatUrlName); //получение timeset для правой колонки и главной страницы
            
            $catUrlName = $this->catNameAr[count($this->catNameAr)-1];
            $catConf    = $this->ci->category_m->getCatTimeSet($catUrlName);
            
            if($catConf===false AND $mainCatConf!==false) //подстановка TimeSet родительской категории в случае отсутствия TimeSet для дочерней
            {
                $catConf = $mainCatConf;
            }
            
            if($catConf!==false AND $mainCatConf!==false) //замена полей в дочерней категории 
            {
                $catConf['cache_time_main_page_m']          = $mainCatConf['cache_time_main_page_m'];
                $catConf['cache_time_right_last_news_m']    = $mainCatConf['cache_time_right_last_news_m'];
                $catConf['right_top_news_time_h']           = $mainCatConf['right_top_news_time_h'];
            }
        }
        if(isset($catConf) == false OR $catConf == false)
        {
            $catConf    = $this->ci->category_m->getDefaultTimeSet();
        }
        
        return $catConf;
    }
    
    function getCatFromUri(){
        $pattern = "#([-_a-z\d]+)/#i";
        
        if( preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches) ){
            $this->mainCat      = $matches[1][0];
            $this->catNameAr    = $matches[1];    
            return $matches[1];
        }
    }
}

