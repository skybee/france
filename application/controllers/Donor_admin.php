<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Donor_admin extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('donor_admin_m');
    }
    
    function main(){
        $this->load->view('donor_admin/main_v');
        $a = $this->donor_admin_m->getDomainList();
        
//        echo '<pre>'.print_r($a,1).'</pre>';
    }
    
    function add_domain(){
        
        if( !empty($_POST['subname']) && !empty($_POST['name']) && !empty($_POST['hosting']) && !empty($_POST['account']) ){
            $arr['anser']   = $this->donor_admin_m->add_domain( $_POST );
        }
        else{
            $arr['anser']   = '! Одно из полей не заполненно <br /><pre>'.print_r($_POST,1).'</pre>';
        }
        $arr['back']    = $_SERVER['HTTP_REFERER'];
        
        $this->load->view('donor_admin/add_anser_v', $arr);
    }
    
}

