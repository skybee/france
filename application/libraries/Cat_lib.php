<?php

class Cat_lib{
    
    private $mainCat = '', $catNameAr = array() ;
    
    function __construct() {
        $this->ci = &get_instance();
    }
    
    function getCatConfig(){
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
    
    function getCatFromUri(){
        $pattern = "#([-_a-z\d]+)/#i";
        
        if( preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches) ){
            $this->mainCat      = $matches[1][0];
            $this->catNameAr    = $matches[1];    
            return $matches[1];
        }
    }
}

