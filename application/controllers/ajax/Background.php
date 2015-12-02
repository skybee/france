<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 


class Background extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->database();
        $this->load->model('article_m');
    }
    
    function set_top(){
        if( $_SERVER["REQUEST_METHOD"] != 'POST' ) exit('! Wrong Method');
        
        $id     = (int) $_POST['docId'];
        $ip     = $_SERVER['HTTP_X_REAL_IP'];
        $ref    = $_POST['ref'];
        $rank   = 1;
        
        if( preg_match("#^66\.249\.\d{1,3}\.\d{1,3}#i", $ip) ) exit('Lock IP');
        
        if( preg_match("#(google|yandex|mail.ru|Nigma|Rambler|Ukr.net|Bing)#i", $ref) ){
            $rank = 3;
//            $this->steSearchQuery( $id, $ref );
        }
        
        $this->article_m->set_article_rank($id, $ip, $rank);
    }
    
//    private function steSearchQuery( $id, $ref ){
//        
//        echo $id."\n".$ref;
//        
//        if( preg_match("#yandex#i", $ref) ){
//            $refAr      = parse_url($ref);
//            parse_str($refAr['query'], $refUriAr);
//            print_r($refAr);
//            print_r($refUriAr);
//            
//            echo base64_decode($refUriAr['etext']);
//        }
//    }
    
    function get_right_hc(){        
        if( isset($_COOKIE['country']) && mb_strlen($_COOKIE['country']) == 2  ){
            $country = $_COOKIE['country'];
        }
        else{
            $country = $this->getCountrySetCoockie();
        }
        
        if( $country == 'UA'){
            $this->load->view('ads/house_v');
        }
    }
    
    private function getCountrySetCoockie(){
        $this->load->helper('geoip/geoip_helper');
        
        $country = get_country();
        
        setcookie('country', $country, time()+3600*24 );
        
        return $country;
    }
}