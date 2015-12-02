<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News_parser_lib extends parse_lib {

    function __construct() {
        parent::__construct();

        $this->CI->load->model('parser_m');
    }

    function get_like_news_hash($new_article_hash_ar, $article_limit = 20) {

        if (count($new_article_hash_ar) < 1) {
            echo "Масив хешей пуст\n";
            return FALSE;
        }

        shuffle($new_article_hash_ar);

        $cnt_ar = count($new_article_hash_ar);
        $hash_list_str = '';
        for ($i = 0; $i < 10 && $i < $cnt_ar; $i++) {
            if ($i)
                $hash_list_str .= ', ';
            $hash_list_str .= "'{$new_article_hash_ar[$i]}'";
        }

        $sql = "SELECT `shingles`.`hash`, `shingles`.`article_id` 
                FROM `shingles`, 
                ( 	
                    SELECT DISTINCT `article_id` 
                    FROM `shingles` 
                    WHERE `hash` IN ({$hash_list_str}) 
                ) AS `t1` 
                WHERE `shingles`.`article_id` = `t1`.`article_id`";

//        echo $sql;

        $query = $this->CI->db->query($sql);

        if ($query->num_rows() < 1)
            return FALSE;

        $result_ar = array();
        foreach ($query->result_array() as $row) {
            $result_ar[$row['article_id']][] = $row['hash'];
        }

        return $result_ar;
    }

    function insert_news($data_ar, $count_word = 10) { //принимает массив array('url','img','title','text','date') и минимальный размер текста(колличество шинглов/5) ; 
        
        $data_ar['text'] = $this->clear_txt($data_ar['text']);
        $data_ar['title'] = $this->CI->db->escape_str(strip_tags($data_ar['title']));
        $data_ar['donor'] = $this->get_donor_url($data_ar['url']);
        $this_hash_ar = $this->get_shingles_hash($data_ar['text']);

        if (count($this_hash_ar) < $count_word) {
            echo "error #1 small text <br />\n";
            return FALSE;
        }

        $like_hash_list = $this->get_like_news_hash($this_hash_ar, 20);

        if( $like_hash_list != false ){ //сравнение хешей
            foreach( $like_hash_list as $news_id => $like_hash_ar ){
                if( $this->comparison_shingles_hash($this_hash_ar, $like_hash_ar, 50) == true ){ //если найденно совпадение текста
                    echo "error #2 clone text. CloneID-".$news_id.' '.$data_ar['title']."<br />\n";
                    return FALSE;
                }
            }
         }   

        
        $data_ar['text'] = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
        $data_ar['img_name'] = $this->load_img($data_ar['img'], $data_ar['url']);
        if ($data_ar['img_name']) {
            $this->resizeImg('medium');
            $this->resizeImg('small');
        }
//        $data_ar['url_name'] = seoUrl($data_ar['title']);
        $data_ar['url_name']    = url_slug( $data_ar['title'] ,array('transliterate' => true));
        
        $sql = "   INSERT INTO `article` 
                    SET
                        `title`         = '{$data_ar['title']}', 
                        `text`          = '" . $this->CI->db->escape_str($data_ar['text']) . "',
                        `cat_id`        = '{$data_ar['cat_id']}',    
                        `main_img`      = '{$data_ar['img_name']}',
                        `date`          = '{$data_ar['date']}',
                        `url_name`      = '{$data_ar['url_name']}',
                        `donor`         = '{$data_ar['donor']}',
                        `scan_url_id`   = '{$data_ar['scan_url_id']}',
                        `author_id`     = '0',
                        `donor_id`      = '{$data_ar['donor_id']}'
                ";            

        $this->CI->db->query($sql);               
                        
        $article_id = $this->CI->db->insert_id();

        if ($article_id) { #ID is "0" becouse "INSERT DELAYED INTO"
            $this->CI->parser_m->add_shingles($this_hash_ar, $article_id);
        }

        echo 'ОК - Занесена новая новость ID# ' . $article_id . ' - ' . $data_ar['title'] . "<br />\n";
        return TRUE;
    }
    
    function tmp_upd_news($data_ar, $id) { //принимает массив array('url','img','title','text','date') и минимальный размер текста(колличество шинглов/5) ; 
        
        $data_ar['text'] = $this->clear_txt($data_ar['text']);
        $data_ar['title'] = $this->CI->db->escape_str(strip_tags($data_ar['title']));

        
        $data_ar['text'] = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
        $data_ar['img_name'] = $this->load_img($data_ar['img'], $data_ar['url']);
        if ($data_ar['img_name']) {
            $this->resizeImg('medium');
            $this->resizeImg('small');
        }
        
        $sql = "   UPDATE `article` 
                    SET
                        `title`         = '{$data_ar['title']}', 
                        `text`          = '" . $this->db->escape_str($data_ar['text']) . "',
                        `main_img`      = '{$data_ar['img_name']}'
                    WHERE `id` = {$id}        
                ";            

        if( strlen($data_ar['title']) > 20 &&  strlen($data_ar['text']) > 200 ){            
            $this->CI->db->query($sql);
        }

//        $article_id = $this->CI->db->insert_id();
        $article_id = $id;

//        if ($article_id) {
//            $this->CI->parser_m->add_shingles($this_hash_ar, $article_id);
//        }

        echo 'ОК - Обновлена новость ID# ' . $article_id . ' - ' . $data_ar['title'] . "<br />\n";
        return TRUE;
    }

}
