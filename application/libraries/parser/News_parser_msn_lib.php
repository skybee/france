<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News_parser_msn_lib extends News_parser_lib{
    
    function __construct() {
        parent::__construct();
    }
    
    function insert_news($data_ar, $count_word = 10) { //принимает массив array('url','img','title','text','date') и минимальный размер текста(колличество шинглов/5) ; 
        
//        $data_ar['text'] = $this->clear_txt($data_ar['text']);
        $data_ar['title'] = $this->CI->db->escape_str(strip_tags($data_ar['title']));
//        $data_ar['donor'] = $this->get_donor_url($data_ar['url']);

        $txtLenth = $this->txtLenth($data_ar['text']);
        
        if ($txtLenth < 600) {
            echo "error #1 small text <br />\n";
            return FALSE;
        }
        
        $donorObj   = new donorMsn($data_ar['donor-data']);
        $donorId    = $donorObj->getId();
        
        $data_ar['text'] = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
        $data_ar['img_name'] = $this->load_img($data_ar['img'], $data_ar['url'], $data_ar['title']);
        
        if ($data_ar['img_name']) {
//            $this->resizeImg('medium');
            $this->resizeImg('small');
        }

        $data_ar['url_name']    = url_slug( $data_ar['title'] ,array('transliterate' => true));
        
        $data_ar['title']   = html_entity_decode($data_ar['title'], ENT_QUOTES, 'UTF-8');
        $data_ar['text']    = html_entity_decode($data_ar['text'],  ENT_QUOTES, 'UTF-8');
        
//        $sql = "   INSERT INTO `article` 
//                    SET
//                        `title`         = '{$data_ar['title']}', 
//                        `description`   = '".$this->CI->db->escape_str($data_ar['description'])."',    
//                        `text`          = '".$this->CI->db->escape_str($data_ar['text']) ."',
//                        `cat_id`        = '{$data_ar['cat_id']}',    
//                        `main_img`      = '{$data_ar['img_name']}',
//                        `date`          = '{$data_ar['date']}',
//                        `url_name`      = '{$data_ar['url_name']}',
//                        `scan_url_id`   = '{$data_ar['scan_url_id']}',
//                        `donor_id`      = '{$donorId}',
//                        `canonical`     = '{$data_ar['canonical']}'    
//                "; 
                        
        $sql2 =  "  INSERT INTO `article` 
                    SET
                        `title`         = ?, 
                        `description`   = ?,    
                        `text`          = ?,
                        `cat_id`        = '{$data_ar['cat_id']}',    
                        `main_img`      = '{$data_ar['img_name']}',
                        `date`          = '{$data_ar['date']}',
                        `url_name`      = '{$data_ar['url_name']}',
                        `scan_url_id`   = '{$data_ar['scan_url_id']}',
                        `donor_id`      = '{$donorId}',
                        `canonical`     = '{$data_ar['canonical']}'    
                ";               

//        echo $sql;                
                        
//        $this->CI->db->query($sql);
        $this->CI->db->query($sql2, array($data_ar['title'], $data_ar['description'], $data_ar['text']));
        
        $article_id = $this->CI->db->insert_id();

        echo 'ОК - Занесена новая новость ID# ' . $article_id . ' - ' . $data_ar['title'] . "<br />\n";
        return TRUE;
    }
    
    function txtLenth($html){
        $text   = strip_tags($html);
        $text   = preg_replace("#[/,:;\!\?\(\)\.\s]#i", '', $text);
        $lenth  = mb_strlen($text);
        
        return $lenth;
    }
    
    function clear_txt( $html ){
        $html = preg_replace("#<script[\s\S]*?</script>#i", '', $html);
        $html = preg_replace("#<iframe[\s\S]*?</iframe>#i", '', $html);
        $html = strip_tags($html, '<p> <img> <table> <tr> <td> <h1> <h2> <h3> <em> <i> <b> <strong> <ul> <ol> <li> <br> <center>');
        $html = parse_lib::uncomment_tags($html); //возврат закомментированного содержимого
        $html = $this->close_tags($html);
        
        return $html;
    }
}


class donorMsn{
    
    private $donorData;
    
    
    function __construct($donorData) {
        $this->donorData = $donorData;
        $this->ci = & get_instance();
    }
    
    
    function getId(){
        $getSql = "SELECT `id`, `upd` FROM `donor` WHERE `host`='{$this->donorData['host']}' LIMIT 1";
        $query  = $this->ci->db->query($getSql); 
        
        if ($query->num_rows() < 1) // if no donor in db
        { 
            return $this->addDonor();
        }
        
        $row = $query->row_array();
        
        $timeUpd = strtotime("+1 day", strtotime($row['upd']));
        if(time()>$timeUpd)
        {
            $this->updDonor($row['id']);
        }
        
        return $row['id'];
    }
    
    
    private function addDonor(){
        
        $date       = date("Y-m-d H:i:s");
        $imgName    = $this->loadImg();
        
        $sql = "INSERT INTO `donor` "
                . "SET "
                . "`name`='{$this->ci->db->escape_str($this->donorData['name'])}', "
                . "`host`='{$this->donorData['host']}', "
                . "`img`='{$imgName}', "
                . "`upd`='{$date}'";
                
        $this->ci->db->query($sql);
        
        return $this->ci->db->insert_id();
    }
    
    
    private function updDonor($id){
        
        $date       = date("Y-m-d H:i:s");
        $imgName    = $this->loadImg();
        
        $sql = "UPDATE `donor` "
                . "SET "
                . "`name`='{$this->ci->db->escape_str($this->donorData['name'])}', "
                . "`host`='{$this->donorData['host']}', "
                . "`img`='{$imgName}', "
                . "`upd`='{$date}' "
                . "WHERE `id`='{$id}' "
                . "LIMIT 1";
                
        $this->ci->db->query($sql);        
    }
    
    
    private function loadImg(){
        $imgData = Parse_lib::down_with_curl($this->donorData['img']);
        $ImgFileName = $this->donorData['host'].'.png';
        $ImgFilePath = './upload/_donor-logo/'.$ImgFileName;
        
        if(!empty($imgData)){
            file_put_contents($ImgFilePath, $imgData);
        }
        
        return $ImgFileName;
    }
    
}

