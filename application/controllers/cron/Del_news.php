<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Del_news extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->database();
    }
    
    function index(){}
    
    function del_old_not_popular_news($cnt=1){
        header("Content-type:text/plain; Charset=utf-8");
        set_time_limit(300);
        
        $cnt    = (int) $cnt;
        $date   = date("Y-m-d",strtotime("- 2 month"));
        $sql    = "SELECT `id` FROM  `article` "
                . "WHERE  "
                . "`views`=0 "
                . "AND  "
                . "`date`<'{$date}' "
                . "AND  "
                . "`cat_id` IN ( 4, 5, 6, 7, 8, 9, 10, 11 ) "
                . "LIMIT {$cnt}";
                
        $query = $this->db->query($sql);
        
        if($query->num_rows()<1)
        {
            exit('Нет записей для удаления'."\n");
        }
        
        foreach($query->result_array() as $row)
        {
            $this->del_news($row['id'], $this->get_code());
            flush();
        }
    }
    
    function del_news($id,$code){
        $code = (int) $code;
        if($code != $this->get_code())
        {
            exit("Code Error");
        }
        
        $msg    = "\n\n -- Del ID: {$id} -- \n\n";
        $sql    = "SELECT `text`, `main_img` FROM `article` WHERE `id`='$id' ";
        $query  = $this->db->query($sql);
        
        if($query->num_rows() < 1)
        {
            return "ID: {$id} - запись не найдена \n";
        }
        $row = $query->row_array();
        
        if(!empty($row['main_img']))
        {
            $msg .= $this->del_img('./upload/images/real/'.$row['main_img']);
            $msg .= $this->del_img('./upload/images/medium/'.$row['main_img']);
            $msg .= $this->del_img('./upload/images/small/'.$row['main_img']);
        }
        
        $imgPathAr = $this->get_img_from_txt($row['text']);
        
        if(count($imgPathAr)>0)
        {
            foreach($imgPathAr as $imgPath)
            {
                $msg .= $this->del_img('.'.$imgPath);
            }
        }
        
        $msg .= $this->del_data($id);
        
        echo $msg;
    }
    
    
    private function del_img($fName){
        $msg = '';
        if(is_file($fName))
        {
            if(unlink($fName)){
                $msg .= 'OK: Файл удален - '.$fName."\n";
            }
            else{
                $msg .= 'ERROR: Ошибка удаления файла - '.$fName."\n";
            }
        }
        else{
            $msg .= 'NOTE: Файл не найден - '.$fName."\n";
        }
        
        return $msg;
    }
    
    private function get_img_from_txt($html){
        $searchPattern = "#src=['\"](/upload/images/\S+?)['\"]#i";
        
        preg_match_all($searchPattern, $html, $matches);
        
        if(count($matches[1]) < 1)
        {
            return NULL;
        }
        
        $imgPathAr = array();
        
        foreach ($matches[1] as $imgPath)
        {
            $imgPathAr[] = $imgPath; 
        }
        
        return $imgPathAr;
    }
    
    private function del_data($id){
        $msg = '';
        if($this->db->query("DELETE FROM `article` WHERE `id`='{$id}' LIMIT 1")){
            $msg .= 'OK: article'."\n";
        }
        else{
            $msg .= 'ERROR: article'."\n";
        }
        
        if($this->db->query("DELETE FROM `article_like_id` WHERE `article_id`='{$id}' LIMIT 1")){
            $msg .= 'OK: article_like_id'."\n";
        }
        else{
            $msg .= 'ERROR: article_like_id'."\n";
        }
        
        if($this->db->query("DELETE FROM `article_like_serp` WHERE `article_id`='{$id}' LIMIT 1")){
            $msg .= 'OK: article_like_serp'."\n";
        }
        else{
            $msg .= 'ERROR: article_like_serp'."\n";
        }
        
        if($this->db->query("DELETE FROM `article_top` WHERE `article_id`='{$id}' ")){
            $msg .= 'OK: article_top'."\n";
        }
        else{
            $msg .= 'ERROR: article_top'."\n";
        }
        
        if($this->db->query("DELETE FROM `shingles` WHERE `article_id`='{$id}' ")){
            $msg .= 'OK: shingles'."\n";
        }
        else{
            $msg .= 'ERROR: shingles'."\n";
        }
        
        return $msg;
    }
    
    private function get_code(){
        return date("dmy");
    }
}