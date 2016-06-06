<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 


class Main extends CI_Controller{
    
    
    function __construct() {
        parent::__construct();
        
        $this->load->database();
        $this->load->model('article_m');
        $this->load->helper('date_convert');
        $this->load->driver('cache');
        
        $this->load->config('multidomaine');
        $this->load->library('multidomaine_lib');
        
        $this->multidomaine = $this->multidomaine_lib->getHostData();
    }
    
    
    function get_top_news()
    {   
        $cacheName = 'api_top_news';
        
        if( !$returnAr = $this->cache->file->get($cacheName) ){
            $newsAr = $this->article_m->get_popular_articles(1,6,72);

            if(count($newsAr)<1){ return false; }

            $returnAr['host_data']  = $this->multidomaine;
            $returnAr['news']       = $newsAr;
            
            $this->cache->file->save($cacheName, $returnAr, 20 );
        }
        
        header('Content-Type: application/json');
        echo json_encode($returnAr);
    }
}