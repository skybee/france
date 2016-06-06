<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



function check_lock_donor($host, $lockHosts){
    $host = trim($host);
    if(is_array($lockHosts)===false OR count($lockHosts)<1)
    {
        return false;
    }
    
    
    foreach($lockHosts as $lHost)
    {
        $pattern = "#{$lHost}$#i";
        
        if(preg_match($pattern, $host))
        {
            return true;
        }
    }
    
    return false;
}