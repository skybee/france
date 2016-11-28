<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Multidomaine_lib
{
    function __construct() {
        $this->ci = &get_instance();
    }
    
    function getHostData()
    {
        $multidomaineAll    = $this->ci->config->item('multidomaine');
        $hostSet            = $multidomaineAll['host_set'];
        $thisHost           = $_SERVER['HTTP_HOST'];
        
        if(isset($hostSet[$thisHost]))
        {
            $this->multidomaine = $multidomaineAll[$hostSet[$thisHost]];
        }
        else
        {
            $this->multidomaine = $multidomaineAll['ru'];
        }
        
        return $this->multidomaine;
    }
    
    function getHostsList(){
        $multidomaineAll    = $this->ci->config->item('multidomaine');
        $aliases = $multidomaineAll['aliases'];
        
        foreach ($multidomaineAll['host_set'] as $host => $c_code){
            if(in_array($host, $aliases)){continue;}
            $hostsList[] = $host;
        }
        
        return $hostsList;
    }
}