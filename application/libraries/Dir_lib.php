<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');




class Dir_lib {
    
    private $datePath,
            $mainDir    = 'upload/',
            $imgDir     = 'images/',
            $moveDir    = 'move/',
            $sDir       = 'small/',
            $mDir       = 'medium/',
            $rDir       = 'real/';
    
    function __construct() {
        $this->datePath = date("Y/m/d/");
    }
    
    static function createDir( $dir ){
        
        $dir    = rtrim($dir,'/');
        $dirAr  = explode('/', $dir);
        
        if( count($dirAr) < 1 ) return false;
        
        $checkPath = '';
        foreach( $dirAr as $nextDir ){
            if( empty($checkPath) )
                $newCheckPath = $nextDir;
            else
                $newCheckPath = $checkPath.'/'.$nextDir;
            
            if( !is_dir($newCheckPath) )
                mkdir($newCheckPath);
            
            $checkPath = $newCheckPath;
        }
    }
    
    function getImgSdir(){
        return $this->getImgDir( $this->sDir );
    }
    
    function getImgMdir(){
        return $this->getImgDir( $this->mDir );
    }
    
    function getImgRdir(){
        return $this->getImgDir( $this->rDir );
    }
    
    function getDatePath(){
        return $this->datePath;
    }
    
    private function getImgDir( $sizeDir ){
        $dir = $this->mainDir.$this->imgDir.$sizeDir.$this->datePath;
        if( !is_dir($dir) ){
            self::createDir($dir);
        }
        return $dir;
    }
}