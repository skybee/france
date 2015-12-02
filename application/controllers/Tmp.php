<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tmp extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->database();
    }
    
    function index(){
        echo 'Tmp Index Controller';
    }
    
    function _chenge_all_cat_uri(){
        $query = $this->db->query("SELECT `id` FROM  `category` ORDER BY  `id`");
        
        foreach ($query->result_array() as $row){
            $this->chenge_cat_uri( $row['id'] );
        }
        
        echo '<br /> Все изменения завершены<br /><br />';
        
        $this->change_sub_cat_id();
    }
      
    private function chenge_cat_uri( $cat_id ){
        $this->load->model('category_m');
        
        $cat_url = $this->category_m->change_cat_full_uri( $cat_id );
        
        if( $cat_url ){
            echo 'В категории ID: '.$cat_id.' URL изменен на: '.$cat_url.'<br />';
        }
        else{
            echo 'URL не изменен ID: '.$cat_id.'<br />';
        }
    }
       
    private function get_sub_cat_id( $id = 0 ){
        $sql        = "SELECT `id` FROM `category` WHERE `parent_id` = '{$id}' ";
        $query      = $this->db->query( $sql );
        $pIdList    = '';
        
        if( $query->num_rows() < 1 ) return ''; 
        
        foreach( $query->result_array() as $row){
            $pIdList   .= ','.$row['id'];
            $query2     = $this->db->query("SELECT `id` FROM `category` WHERE `parent_id` = '{$row['id']}'");
            if( $query2->num_rows() > 0 ){
                $pIdList .= ','.$this->get_sub_cat_id( $row['id'] );
            }
        }
        
        return trim( $pIdList, ',');
    }
    
    private function change_sub_cat_id(){
        $sql = "SELECT `id` FROM `category` ORDER BY `id` ";
        $query = $this->db->query($sql);
        
        foreach( $query->result_array() as $row ){
            $subId = $this->get_sub_cat_id($row['id']);
            
            if( !empty($subId) ){
                $this->db->query("UPDATE `category` SET `sub_cat_id`='{$subId}' WHERE `id` = '{$row['id']}' ");
                echo 'ID: '.$row['id']."<br />\n".$subId."<br /><br />\n\n";
            }
        }
    }
    
//    function womens( $page = 1 ){
//        $this->load->helper('parser/download_helper');
//        
//        $url = 'http://www.womenshealthmag.com/channel_ui/ajax/'.$page.'/5';
//        
//        $json = down_with_curl($url);
//        
//        $jsonAr = json_decode($json);
//        
//        print_r($jsonAr);
//    }
    
    function _create_cache_page_on_server($cat_name, $cnt_page = 1){
        set_time_limit(7200);
        
        $this->load->helper('parser/download_helper');
        
        for($i=1; $i<=$cnt_page; $i++){
            $url = "http://odnako.su/sitemap_link_page/{$cat_name}/{$i}/";
            
            $html = down_with_curl($url);
            
            if( !empty($html) ){
                echo $cat_name." page - {$i} OK <br />\n";
            }
            else{
                echo $cat_name." page - {$i} Error <br />\n";
            }
        }
        flush();
        sleep(1);
    }
    
    function _migrate_to_like_serp_tbl(){
        set_time_limit(7200);
        header("Content-type:text/plain;Charset=utf-8");
        
        $i = 1;
        while($i<=12000)
        {
            $sql = "SELECT "
                    . "`article`.`id`, `article`.`serp_object`, `article`.`serp_update` "
                    . "FROM `article` "
                    . "WHERE "
                    . "`article`.`id`  NOT IN (SELECT `article_like_serp`.`article_id`  FROM `article_like_serp`) "
                    . "LIMIT 3000";

            $query  = $this->db->query($sql);
            if( $query->num_rows() < 1 )
            { 
                echo "\n\n-== ERROR (SELECT) ==-\n\n".$sql;
                break;
            }
            
            $value = 'VALUES '; $ii = 0;
            foreach( $query->result_array() as $row)
            {
                if($ii){
                    $value .= ", \n";
                }
                $value .= " ('{$row['id']}', '".$this->db->escape_str($row['serp_object'])."', '{$row['serp_update']}' )";
                $i++;
                $ii++;
            }
            $query->free_result();
            
            
//            $row    = $query->row_array();

//            $sql_insert = "INSERT INTO `article_like_serp` "
//                        . "SET `article_id`='{$row['id']}', `serp_object`='".$this->db->escape_str($row['serp_object'])."', `serp_update`='{$row['serp_update']}' ";
            $sql_insert = "INSERT IGNORE INTO `article_like_serp` (`article_id`, `serp_object`, `serp_update`) {$value} ";            
                        
            if( $this->db->query($sql_insert) )
            {
                echo "{$i}.\tID:\t{$row['id']}\t-OK\n";
            }
            else
            {
                echo "\n\n-== ERROR (INSERT) ==-\n\n".$sql_insert;
            }
//            $i++;
            flush();
//            sleep(3);
        }
        
        echo "\n\n-== END ==-\n\n";
    }
    
    function _migrate_to_like_serp_tbl_second(){
        set_time_limit(7200);
        header("Content-type:text/plain;Charset=utf-8");
        
        $sql_id = "SELECT "
                    . "`article`.`id` "
                    . "FROM `article` "
                    . "WHERE "
                    . "`article`.`id`  NOT IN (SELECT `article_like_serp`.`article_id`  FROM `article_like_serp`) "
                    . "LIMIT 30000";
        
        $query = $this->db->query($sql_id);
        
        if($query->num_rows()<1){ exit("\n\n-== ROW NOT EXIST ==-\n\n");}
        
        $idAr = array();
        
        foreach( $query->result_array() as $row )
        {
            $idAr[] = $row['id'];
        }
        $query->free_result();
        unset($row);
        
        $cntId = count($idAr);
        
        for($i=0;$i<$cntId;$i++)
        {
            $sql = "SELECT `id`, `serp_object`, `serp_update` "
                    . "FROM "
                    . "`article` "
                    . "WHERE `id`='{$idAr[$i]}' "
                    . "LIMIT 1";
                    
            $query = $this->db->query($sql);
            if( $query->num_rows() < 1) { continue;}
            
            $row    = $query->row_array();
            $query->free_result();
            
            $sql_insert = "INSERT IGNORE INTO `article_like_serp` "
                        . "SET `article_id`='{$row['id']}', `serp_object`='".$this->db->escape_str($row['serp_object'])."', `serp_update`='{$row['serp_update']}' ";
                        
            if( $this->db->query($sql_insert) )
            {
                echo "{$i}.\tID:\t{$row['id']}\t-OK\n";
            }
            else
            {
                echo "{$i}.\tID:\t{$row['id']}\t-ERROR\n";
            }
            flush();           
        }
    }
    
    function _change_title_in_pda(){
        set_time_limit(300);
        $this->load->helper('parser/download');
        $this->load->helper('parser/simple_html_dom');
        $this->load->helper('parser/url_name2');
        $this->load->library('parser/Parse_page_lib');
        $this->load->library('parser/Parse_lib');
         $this->load->library('parser/Video_replace_lib');
        
        $sql = "SELECT `article`.`id`, `article`.`donor`, `scan_url`.`url` "
                . "FROM `article` "
                . "LEFT JOIN `scan_url` ON `article`.`scan_url_id` = `scan_url`.`id` "
                . "WHERE "
                . "`article`.`donor` = '4pda.ru' "
                . "AND "
                . "`article`.`title` = '' "
                . "LIMIT 50";
        
        $query = $this->db->query($sql);
        
        foreach($query->result_array() as $row){
            echo $row['id'].' - '.$row['donor'].' - '.$row['url']."<br />\n";
            
            $html = down_with_curl($row['url']);
            
            $data = $this->parse_page_lib->get_data($html, array('host'=>$row['donor']));
            
            $title      = $data['title'];
            $urlName    = url_slug( $title ,array('transliterate' => true));
            
            $this->db->query("UPDATE `article` SET `title`='{$title}', `url_name`='{$urlName}' WHERE `id`='{$row['id']}' LIMIT 1 ");
            
            sleep(3);
        }
    }
}