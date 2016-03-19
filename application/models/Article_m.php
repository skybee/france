<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    
    function get_cat_data_from_url_name( $url_name ){
        $url_name = $this->db->escape_str( $url_name );
        $query = $this->db->query("SELECT * FROM `category` WHERE `url_name` = '{$url_name}' ");
        
        return $query->row_array();
    }
    
    function get_last_news( $cat_id, $cnt = 1, $img = false, $formatDate = false /*, $parentCat = false*/ ){
        
        if ($img)
            $img_sql = " AND `article`.`main_img` != '' ";
        else
            $img_sql = "";
                
        $hourAgo    = $this->catConfig['right_top_news_time'];
        $dateStart  = date("Y-m-d H:i:s", strtotime(" - {$hourAgo} hours" ) );
        
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = '{$cat_id}' ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " `article`.`cat_id` IN ({$cat_id},{$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = " `article`.`cat_id` = '{$cat_id}' ";
        }        
                
        $sql = "SELECT "
                . "`article`.`id`, `article`.`date`, `article`.`url_name`, `article`.`title`, "
                . "\n-- `article`.`text`, \n"
                . "`article`.`main_img`, "
                . "`category`.`full_uri`, "
                . "`donor`.`img` AS 'd_img', `donor`.`name` AS 'd_name'  "
                . "FROM "
                . " `article` "
                . "LEFT OUTER JOIN `category` ON `article`.`cat_id` = `category`.`id` "
                . "LEFT OUTER JOIN `donor` ON `article`.`donor_id` = `donor`.`id` "
                . "WHERE "
                . "`article`.`date`>= '{$dateStart}' "
                . "AND "
                . " {$subCatWhere} "
                . $img_sql
                . " ORDER BY `article`.`id` DESC LIMIT {$cnt} ";  
                

     
        $query = $this->db->query($sql);

        $result_ar = array();
        foreach ($query->result_array() as $row) {
            if ($formatDate) {
                $row['date_ar'] = get_date_str_ar($row['date']);
            }
            $result_ar[] = $row;
        }
        
        return $result_ar;
    }
    
    function get_last_left_news( $idParentId, $cnt = 1 ){
        
        $cacheName = 'last_news_'.$idParentId.'_'.$cnt;
        
        if( !$lastNewsCache = $this->cache->file->get($cacheName) ){
//            $data['first']  = $this->get_last_news($idParentId, 1, true, true /*, true*/);
//            $data['first']  = $data['first'][0];
            $data['all']    = $this->get_last_news($idParentId, $cnt, false, true /*, true*/);
//            unset($data['all'][0]);
            $this->cache->file->save($cacheName, $data, $this->catConfig['cache_time']['right_last_news'] * 60 );
        }
        else
            $data = $lastNewsCache;
        
        return $data;
        
    }
    
    function get_mainpage_cat_news( $news_cat_list ){ //принимает массив с id & name категорий
        $result_ar = array();
        foreach( $news_cat_list as $s_cat_ar ){
            $tmp_ar = $this->get_last_news($s_cat_ar['id'], 4, true, false /*, false*/);
            if( $tmp_ar == NULL || count($tmp_ar) < 1 ) continue; 
            $tmp_ar['s_cat_ar']                 = $s_cat_ar;
//            $tmp_ar['s_cat_ar']['full_uri']     = $tmp_ar[0]['full_uri'];
            $result_ar[]                        = $tmp_ar; 
        }
        
        return $result_ar;
    }
    
    function get_doc_data( $id ){
        $id = (int) $id;
//        $query = $this->db->query(" SELECT  `article`.*, 
//                                            `category`.`name` AS 'cat_name', `category`.`full_uri` AS 'cat_full_uri', 
//                                            `donor`.`name` AS 'd_name', `donor`.`img` AS 'd_img', `donor`.`host` AS 'd_host' 
//                                    FROM 
//                                        `article`, `category`, `donor`
//                                    WHERE 
//                                        `article`.`id`  = {$id}
//                                        AND
//                                        `category`.`id` = `article`.`cat_id`
//                                        AND
//                                        `donor`.`id`    = `article`.`donor_id`
//                                    LIMIT 1    
//                                  ");

        $sql = "SELECT  `article`.`id`, `article`.`cat_id`, `article`.`date`, `article`.`url_name`, `article`.`title`, 
                        `article`.`text`, `article`.`main_img`, `article`.`donor`, `article`.`donor_id`, `article`.`scan_url_id`,                                             `category`.`name` AS 'cat_name', `category`.`full_uri` AS 'cat_full_uri', 
                        `donor`.`name` AS 'd_name', `donor`.`img` AS 'd_img', `donor`.`host` AS 'd_host',
                        `article_like_serp`.`serp_object`
                FROM 
                    `article` 
                    LEFT JOIN  `category` ON  `article`.`cat_id` =  `category`.`id` 
                    LEFT JOIN  `donor` ON  `article`.`donor_id` =  `donor`.`id` 
                    LEFT JOIN  `article_like_serp` ON  `article`.`id` =  `article_like_serp`.`article_id`
                WHERE 
                    `article`.`id`  = {$id}
                LIMIT 1";            
        
        $query = $this->db->query($sql);
        
        if( $query->num_rows() < 1 ) return FALSE; 
        
        $returnAr = $query->row_array();
        $returnAr['date_ar'] = get_date_str_ar( $returnAr['date'] );
        
        return $returnAr;
    }
    
    function get_page_list( $cat_id, $page, $cnt = 15, $text_len = 200 ){
        $stop   = $page * $cnt;
        $start  = $stop - $cnt;
        
        // < subCatId >
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = '{$cat_id}' ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " `article`.`cat_id` IN ({$cat_id},{$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = " `article`.`cat_id` = '{$cat_id}' ";
        }
        // < /subCatId >
        
//        $sql = "SELECT "
//                . "`article`.*, "
//                . "`category`.`full_uri`,"
//                . "`donor`.`name` AS 'd_name', `donor`.`img` AS 'd_img' "
//                . "FROM "
//                . "`article`, `donor`, `category` "
//                . "WHERE "
//                . " {$subCatWhere} "
//                . "AND "
//                . "`donor`.`id` = `article`.`donor_id`"
//                . "AND "
//                . "`category`.id = `article`.`cat_id`"
//                . "ORDER BY `date` DESC "
//                . "LIMIT {$start}, {$cnt} ";
                
        $sql = "SELECT "
                . "`article`.*, "
                . "`category`.`full_uri`,"
                . "`donor`.`name` AS 'd_name', `donor`.`img` AS 'd_img' "
                . "FROM "
                . "`article` "
                . "LEFT JOIN  `donor` ON  `article`.`donor_id` =  `donor`.`id` "
                . "LEFT JOIN  `category` ON  `article`.`cat_id` =  `category`.`id` "
                . "WHERE "
                . " {$subCatWhere} "
                . "ORDER BY `article`.`date` DESC "
                . "LIMIT {$start}, {$cnt} ";        
                
        $query = $this->db->query($sql);
        
        if( $query->num_rows() < 1 ) return FALSE;
        
        $result_ar = array();
        foreach( $query->result_array() as $row){
            $row['text']    = $this->get_short_txt($row['description'],$text_len); #$this->get_short_txt( $row['text'], $text_len );
            $row['date']    = get_date_str_ar( $row['date'] );
            $result_ar[]    = $row;
        }
        
        return $result_ar;
    }
    
    function get_short_txt( $text, $length = 100, $txtFin = 'word' ){
        $text = strip_tags($text);
        $text = mb_substr($text, 0, $length);
        
        if( $txtFin == 'word' ){
            $replacePattern = "# \S+$#i";
            $replace = '';
        }
        elseif( $txtFin == 'dot' ){
            $replacePattern = "#\. [^\.]+$#i";
            $replace = '.';
        }
        
        $text = preg_replace( $replacePattern, $replace, $text );
        
        return $text;
    }
    
    function get_like_articles( $id, $cat_id /*$catParentId*/, $text, $cntNews = 4, $dayPeriod = false, $newsDate = false  ){
        
        $likeIds = $this->get_like_articles_id($id, $newsDate);
        if($likeIds !== false)
        {
            return $this->get_like_articles_from_ids($likeIds);
        }
        
        $cleanPattern = "#(['\"\,\.\\\]+|&\w{2,6};)#i";
        $text = preg_replace($cleanPattern, ' ', $text);
        
        $cntLikeNewsSelect = $cntNews * 3;
        
        if( $dayPeriod && $newsDate ){
            
            $intNewsDate = strtotime( $newsDate );
            
            $dateStart  = date("Y-m-d H:i:s", strtotime(" -{$dayPeriod} day", $intNewsDate ) );
            $dateStop   = date("Y-m-d H:i:s", strtotime(" +{$dayPeriod} day", $intNewsDate ) );
            
            $dateSql = " AND `article`.`date` > '{$dateStart}' AND `article`.`date` < '{$dateStop}' ";
        }
        else
            $dateSql = '';
        
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = ( SELECT `parent_id` FROM `category` WHERE  `id` = '{$cat_id}' LIMIT 1) LIMIT 1  ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " `article`.`cat_id` IN ({$cat_id},{$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = " `article`.`cat_id` = '{$cat_id}' ";
        }
                        
        $sql = "SELECT 
                    `article`.`id`, `article`.`title`, `article`.`url_name`, `article`.`main_img`, `article`.`date`, `article`.`description`, `article`.`views`, `category`.`full_uri` 
                FROM 
                    `article` LEFT OUTER JOIN `category` ON `article`.`cat_id` = `category`.`id`
                WHERE 
                    MATCH (`article`.`title`,`article`.`text`) AGAINST ('{$text}') 
                AND
                     {$subCatWhere} 
                AND 
                    `article`.`id` != '{$id}' 
                {$dateSql} 
                LIMIT {$cntNews}
                ";      
                
        $query = $this->db->query( $sql );
        
        if( $query->num_rows() < 1 ) return NULL;
        
        $result = array();
        foreach( $query->result_array() as $row ){
//            $row['text']    = $this->get_short_txt( $row['text'], 600, 'dot' );
            $row['date_ar'] = get_date_str_ar( $row['date'] );
            $result[] = $row;
        }
        
        $this->insert_like_article_id($id,$result);
        
        return $result;
    }
    
    function get_pager_ar( $cat_id, $page = 1, $cnt_on_page = 15, $page_left_right = 3 ){
        
        // < subCatId >
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = '{$cat_id}' ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " `article`.`cat_id` IN ({$cat_id},{$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = " `article`.`cat_id` = '{$cat_id}' ";
        }
        // < /subCatId >
        
        
        $query_str = "  SELECT 
                            COUNT(*) AS 'count'
                        FROM 
                            `article`
                        WHERE
                            {$subCatWhere}
                    ";                   
                            
         $query = $this->db->query($query_str);
         $row   = $query->row();
         $count_goods = $row->count;
         
         $start     = $page - $page_left_right; if( $start < 1 ) $start = 1;
         $cnt_page  = ceil( $count_goods / $cnt_on_page );
         $stop      = $page + $page_left_right; if( $stop > $cnt_page ) $stop = $cnt_page;
         
         $result_ar = array();
         
         if( $page > $page_left_right+1 ){ //дополнение массива первой страницей
             $result_ar[] = 1;
             if( $page != $page_left_right+2 )
                $result_ar[] = '...';
         }    
         
         
         for($i = $start; $i<=$stop; $i++ ){
             $result_ar[] = $i;
         }
         
         if($cnt_page > $stop+1 ){ //дополняет масив последней страницей
             $result_ar[] = '...';
             $result_ar[] = $cnt_page;
         }    
         
         return $result_ar;
    }
    
    function get_popular_articles($cat_id, $cntNews, $hourAgo, $textLength = 200, $img = true, $parentCat = false ){
        
        $dateStart  = date("Y-m-d H:i:s", strtotime(" - {$hourAgo} hours" ) );
        
        if( $img )
            $imgSql = "\n AND `article`.`main_img` != '' "; 
        else
            $imgSql = '';  
          
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = '{$cat_id}' ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " `article`.`cat_id` IN ({$cat_id},{$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = " `article`.`cat_id` = '{$cat_id}' ";
        }
        
        
        $sql = "SELECT  
                    `article`.`id`,  `article`.`date`,  `article`.`url_name`,  `article`.`title`,  `article`.`description`,  `article`.`main_img`,  `category`.`full_uri` 
                FROM  
                    `article` LEFT OUTER JOIN `category` ON `article`.`cat_id` = `category`.`id`
                WHERE    
                    `article`.`date` >  '{$dateStart}'
                    AND
                    {$subCatWhere}
                    {$imgSql}    
                ORDER BY  
                    `article`.`views` DESC, `article`.`id` DESC 
                LIMIT {$cntNews}";
                
        $query = $this->db->query( $sql );
        
        if( $query->num_rows() < 1 ) return NULL;
        
        $result = array();
        
        foreach( $query->result_array() as $row ){
            $row['text']    = $this->get_short_txt($row['description'],$textLength); #$this->get_short_txt( $row['text'], $textLength );
            $row['date']    = get_date_str_ar( $row['date'] );
            $result[]       = $row;
        }
        
        return $result;
    }
    
    function get_top_slider_data( $idParentId, $cntNews, $hourAgo, $textLength = 200, $img = true, $parentCat = false, $cacheName = 'slider' ){
        
        $topSliderCacheName = $cacheName.'_'.$idParentId;
        if( !$sliderCache = $this->cache->file->get($topSliderCacheName) ){
            $data = $this->get_popular_articles( $idParentId, $cntNews, $hourAgo, $textLength, $img, $parentCat );
            $this->cache->file->save($topSliderCacheName, $data, $this->catConfig['cache_time']['top_slider'] * 60 );
        }
        else
            $data = $sliderCache;
        
        return $data;
    }
    
    function get_search_page_list( $searchStr, $page, $cnt = 15){
        $stop   = $page * $cnt;
        $start  = $stop - $cnt;
        
        $sql = "SELECT "
                . "`article`.*, "
                . "`category`.`full_uri`,"
                . "`donor`.`name` AS 'd_name', `donor`.`img` AS 'd_img' "
                . "FROM "
                . "`article`, `donor`, `category`, "
                . " (   SELECT `id`, MATCH (`title`,`text`) AGAINST ('{$searchStr}') AS `rank` "
                . "     FROM `article`"
                . "     WHERE MATCH (`title`,`text`) AGAINST ('{$searchStr}') "
                . "     LIMIT 150 ) AS `seach` "
                . "WHERE "
                . "`article`.`id`       = `seach`.`id` "
                . "AND "
                . "`article`.`donor_id` = `donor`.`id` "
                . "AND "
                . "`category`.id = `article`.`cat_id`"
                . "ORDER BY `seach`.`rank` DESC "
                . "LIMIT {$start}, {$cnt} ";
                        
        $query = $this->db->query($sql);                
        
        if( $query->num_rows() < 1 ) return FALSE;
        
        $result_ar = array();
        foreach( $query->result_array() as $row){
            $row['text']    = $this->get_short_txt( $row['text'], 200 );
            $row['date']    = get_date_str_ar( $row['date'] );
            $result_ar[]    = $row;
        }
        
        return $result_ar;
    }
    
    function get_search_pager_ar( $searchStr, $page = 1, $cnt_on_page = 15, $page_left_right = 3 ){
                
        $query_str = "  SELECT 
                            COUNT(*) AS 'count'
                        FROM 
                            `article`
                        WHERE
                            MATCH (`title`,`text`) AGAINST ('{$searchStr}')        
                    ";
                            
         $query = $this->db->query($query_str);
         $row   = $query->row();
         $count_goods = $row->count;
         
         if( $count_goods > 150 ) $count_goods = 150;
         
         $start     = $page - $page_left_right; if( $start < 1 ) $start = 1;
         $cnt_page  = ceil( $count_goods / $cnt_on_page );
         $stop      = $page + $page_left_right; if( $stop > $cnt_page ) $stop = $cnt_page;
         
         $result_ar = array();
         
         if( $page > $page_left_right+1 ){ //дополнение массива первой страницей
             $result_ar[] = 1;
             if( $page != $page_left_right+2 )
                $result_ar[] = '...';
         }    
         
         
         for($i = $start; $i<=$stop; $i++ ){
             $result_ar[] = $i;
         }
         
         if($cnt_page > $stop+1 ){ //дополняет масив последней страницей
             $result_ar[] = '...';
             $result_ar[] = $cnt_page;
         }    
         
         return $result_ar;
    }
    
    function set_article_rank($id, $ip, $rank){
        //проверка наличие записи с таким IP и ID в базе
        $query = $this->db->query(" SELECT COUNT(*) AS 'cnt' FROM `article_top` WHERE `article_id` = '{$id}' AND `ip` = '{$ip}' "); 
        $row = $query->row_array();
        
        
        if( $row['cnt'] < 1 ){ //запись новой записи в базу
            $this->db->query("INSERT INTO `article_top` SET `article_id` = '{$id}', `ip` = '{$ip}', `rank` = {$rank} ");
            
            if( rand(1, 1000) <= 50 ){ //удаление старых записей
                $control_date   = date("Y-m-d H:i:s", strtotime("- 30 day", time() ) ); //дата удаления записи
                
                $this->db->query("DELETE FROM `article_top` WHERE `date` < '{$control_date}' ");
            }
        }
    }
    
    private function insert_like_article_id($articleId, $likeArticlesAr){
        $cntLike = count($likeArticlesAr);
        if($cntLike<1) {return false;}
        
        $likeStr = '(';
        for($i=0;$i<$cntLike;$i++)
        {
            $likeStr .= $likeArticlesAr[$i]['id'];
            if($i<$cntLike-1)
            {
                $likeStr .= ', ';
            }
        }
        $likeStr .= ')';
        
        $updTime = date("Y-m-d H:i:s");
        
        $sql = "REPLACE INTO `article_like_id` SET `article_id`='{$articleId}', `like_id`='{$likeStr}', `upd_time`='$updTime'";
        if($this->db->query($sql))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private function get_like_articles_id($articleId, $newsDate){
        $sql    = "SELECT * FROM `article_like_id` WHERE `article_id`='{$articleId}' ORDER BY `upd_time` DESC LIMIT 1 ";
        $query  = $this->db->query($sql);
        if($query->num_rows()<1) {return false;}
        
        $row = $query->row_array();
        
        $timeNow        = time();
        
        $timeCreateNews = strtotime($newsDate); 
        $timeOldNews    = strtotime('+ '.$this->catConfig['like_news_day'].' day', $timeCreateNews);
        
        $timeLastUpd    = strtotime($row['upd_time']);
        
        if($timeOldNews > $timeNow)
        {
            $timeNewUpd     = strtotime('+ '.$this->catConfig['like_news_cache'].' hour', $timeLastUpd);
        }
        else //время обновления для старой новости
        {
            $timeNewUpd     = strtotime('+ '.$this->catConfig['like_news_cache_for_old'].' hour', $timeLastUpd);
        }
        
//        echo "timeCreateNews\t\t".date("Y-m-d H-i", $timeCreateNews).' - '.$timeCreateNews."\n";
//        echo "timeOldNews\t\t".date("Y-m-d H-i",$timeOldNews).' - '.$timeOldNews."\n\n";
//        echo "timeLastUpd\t\t".date("Y-m-d H-i",$timeLastUpd).' - '.$timeLastUpd."\n";
//        echo "timeNewUpd\t\t".date("Y-m-d H-i",$timeNewUpd).' - '.$timeNewUpd."\n\n";
        
        if($timeNow < $timeNewUpd)
        {
            return $row['like_id'];
        }
        else
        {
            return false;
        }
    }
    
    private function get_like_articles_from_ids($idsStr){
        $sql = "SELECT 
                    `article`.`id`, `article`.`title`, `article`.`url_name`, `article`.`main_img`, `article`.`date`, `article`.`description`, `article`.`views`, `category`.`full_uri` 
                FROM 
                    `article`, `category`
                WHERE
                    `article`.`id` IN {$idsStr}
                     AND
                     `category`.`id` = `article`.`cat_id`
                LIMIT 9     
                ";
        $query = $this->db->query($sql); 
        
        if( $query->num_rows() < 1 ) return NULL;
        
        $result = array();
        foreach( $query->result_array() as $row ){
//            $row['text']    = $this->get_short_txt( $row['text'], 600, 'dot' );
            $row['date_ar'] = get_date_str_ar( $row['date'] );
            $result[] = $row;
        }
        
        return $result;
    }
    
}