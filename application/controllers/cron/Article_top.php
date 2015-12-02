<?php

class Article_top extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->database();
    }
    
    function upd_article_view(){
        
        if( $this->single_work( 45, 'upd_article_view') == false ) exit('The work temporary Lock');
        
        $sql = "UPDATE LOW_PRIORITY `article` ,
                (
                    SELECT  `article_id` , SUM(`rank`) AS  `rank` 
                    FROM  `article_top` 
                    GROUP BY  `article_id`
                ) AS  `t1` 
                SET  `article`.`views` =  `t1`.`rank` 
                WHERE  
                `article`.`id` =  `t1`.`article_id` ";
        
        $this->db->query($sql);
    }
    
    private function single_work( $minutes, $fname = 'null' ){
        $lockFile   = 'lock/'.$fname.'.lock';
        $lockTime   = time() + (60*$minutes);
        
        
        if( is_file($lockFile) ){
            $fileTimeLock   = file_get_contents($lockFile);
            $fileTimeLock   = (int) $fileTimeLock;
            
            if( time() < $fileTimeLock ) return FALSE;
        }
            
        file_put_contents($lockFile, $lockTime );
        
        return TRUE;
    }
    
}
