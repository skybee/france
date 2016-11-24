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
    
    function getImgSdir($host=true, $dateStr=true){
        return $this->getImgDir( $this->sDir, $host, $dateStr );
    }
    
    function getImgMdir($host=true, $dateStr=true){
        return $this->getImgDir( $this->mDir, $host, $dateStr );
    }
    
    function getImgRdir($host=true, $dateStr=true){
        return $this->getImgDir( $this->rDir, $host, $dateStr );
    }
    
    function getDatePath(){
        return $this->datePath;
    }
    
    private function getImgDir( $sizeDir, $host=true, $dateStr=true ){
        
        $hostDir = '';
        
        if($host==true){
            $hostDir = $this->getHostDir(); 
        }
        
        if($dateStr){
            $dir = $this->mainDir.$hostDir.$this->imgDir.$sizeDir.$this->datePath;
        }
        else{
            $dir = $this->mainDir.$hostDir.$this->imgDir.$sizeDir;
        }
        
        
        
        if( !is_dir($dir) ){
            self::createDir($dir);
        }
        return $dir;
    }
    
    private function getHostDir(){
        $host = $_SERVER['HTTP_HOST'];
        $host = preg_replace("#^www\.#i", '', $host);
        
        $hostDir = $host.'/';
        
        return $hostDir;
    }
}