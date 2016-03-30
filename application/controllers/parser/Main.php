<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit( 60 ); 

class Main extends CI_Controller
{
    
    function __construct() {
        parent::__construct();
        
//        exit('123');
        
        $this->load->database();
//        $this->load->helper('parser/download');
        $this->load->helper('donor_info_helper');
        $this->load->helper('parser/simple_html_dom');
        $this->load->library('parser/rss_url_lib');
        $this->load->library('parser/parse_lib');
        $this->load->library('parser/news_parser_lib');
        $this->load->library('parser/news_parser_msn_lib');
        $this->load->library('parser/parse_page_lib');
        $this->load->library('parser/video_replace_lib');
        $this->load->model('parser_m');
        $this->load->model('donor_m');
        
        unset( $this->parser_lib );
    }

    function index(){ echo 'index'; }
    
    function get_url_from_rss(){
        header("Content-type:text/html;Charset=utf-8");
        
        if( $this->single_work( 2, 'get_url_from_rss') == false ) exit('The work temporary Lock');
        
        $urls = array(
                        array('url'=>'http://rss.unian.net/site/news_rus.rss',  'host'=>'unian.net'),           //== CAT TRUE        
                        #array('url'=>'http://focus.ua/rss/ru.xml',              'host'=>'focus.ua'),           //== chenge site, no rss
                        array('url'=>'http://news.liga.net/all/rss.xml',        'host'=>'liga.net'),   
                        array('url'=>'http://ru.tsn.ua/rss/',                   'host'=>'tsn.ua'),              //== CAT TRUE !--Good
                        array('url'=>'http://k.img.com.ua/rss/ru/news.xml',     'host'=>'korrespondent.net'),   //== !--Good
                        array('url'=>'http://www.segodnya.ua/xml/rss.html',     'host'=>'segodnya.ua'),         //== CAT TRUE !--Good
                        array('url'=>'http://www.unn.com.ua/rss/news_ru.xml',   'host'=>'unn.com.ua')           //== CAT TRUE
//                       http://rian.com.ua/
//                        'http://gazeta.ua/export/rss/rss.xml',                  //== CAT !FALSE !!!OLD
//                        'http://www.interfax.com.ua/rus/rss/',                  //== CAT TRUE
//                        'http://delo.ua/news/rss/index.xml',                    //== CAT TRUE
//                        //-! 'http://biz.liga.net/all/rss.xml',                      //== CAT TRUE
//                        //-! 'http://finance.liga.net/export/all.xml',               //== CAT TRUE
//                        //-! 'http://blog.liga.net/rss.aspx',                        //== CAT !FALSE            
                        );
        
        foreach ($urls as $urlAr)
        {
            echo "<b>{$urlAr['url']}</b> <br /> \n\n";
            $xml_str = $this->news_parser_lib->down_with_curl($urlAr['url']);
            
            $news_ar = $this->rss_url_lib->get_url( $xml_str );
            
            if( is_array($news_ar) && count($news_ar) > 0 ){
                foreach( $news_ar as $news){
                    $cat_id     = $this->rss_url_lib->get_cat_id( $urlAr['url'], $news['url'], $news['category'] );
                    $donor_id   = $this->donor_m->getDonorIdFromHost( $urlAr['host'] );
                    
                    if( !$donor_id ) echo "<br />! No Donor ID {$urlAr['url']}<br />";
                    if( $cat_id )
                        $this->parser_m->add_to_scanlist( $news['url'], $cat_id, $donor_id );
                }
            }
            
            
            echo "<pre>".print_r( $news_ar, 1 )."</pre>";
        }
    }
    
    function parse_news( $cnt_news = 1 ){
        set_time_limit( 3600 );
        header("Content-type:text/html;Charset=utf-8");
        
//        if( $this->single_work( 5, 'parse_news') == false ) exit('The work temporary Lock');
        
        $parse_list = $this->parser_m->get_news_url_to_parse( $cnt_news );
        
//        echo '<pre>'.print_r($parse_list,1).'</pre>'; exit();
        
        if( $parse_list == null ){  echo "ERROR Отсутствуют URL для сканирования"; return; }
         
        
        $i=1;
        foreach( $parse_list as $news_ar ){
            $this->parser_m->set_url_scaning( $news_ar['id'] );
			
            #<for test>
//            $news_ar['url']     = 'http://www.msn.com/ru-ru/news/politics/%d1%81%d1%8b%d1%80%d1%8c%d0%b5-%d0%b2-%d0%be%d0%b1%d0%bc%d0%b5%d0%bd-%d0%bd%d0%b0-%d0%b8%d0%bd%d0%b2%d0%b5%d1%81%d1%82%d0%b8%d1%86%d0%b8%d0%b8-%d0%b7%d0%b0%d1%87%d0%b5%d0%bc-%d0%b4%d0%bc%d0%b8%d1%82%d1%80%d0%b8%d0%b9-%d0%bc%d0%b5%d0%b4%d0%b2%d0%b5%d0%b4%d0%b5%d0%b2-%d0%b5%d0%b4%d0%b5%d1%82-%d0%b2-%d0%ba%d0%b8%d1%82%d0%b0%d0%b9/ar-BBnvvcV';  
//            $news_ar['host']    = 'www.msn.com';
            #</for test>
            
            echo "<br />\n $i - <i>".$news_ar['url']."</i><br />\n";
            
            $html = $this->news_parser_lib->down_with_curl( $news_ar['url'] );
            
            if( empty($html) ){
                continue;
            }
            
//            $host                           = $this->news_parser_lib->get_donor_url( $news_ar['url'] );
            $insert_data                    = $this->parse_page_lib->get_data( $html, $news_ar );
            if( is_array($insert_data) == false ) continue;
            $insert_data['scan_url_id']     = $news_ar['id'];
            $insert_data['url']             = $news_ar['url'];
            $insert_data['cat_id']          = $news_ar['cat_id'];
            $insert_data['donor_id']        = $news_ar['donor_id'];
            
            if( !isset($insert_data['date']) || $insert_data['date'] == false ){
                $insert_data['date']            = date("Y-m-d H:i:s");
            }
            
            if( !empty($news_ar['main_img_url']) && empty($insert_data['img']) ){ 
                $insert_data['img']         = $news_ar['main_img_url'];
            }
            
//            echo '<pre>'.print_r($news_ar,1).'</pre>';
//            echo '<pre>'.print_r($insert_data,1).'</pre>';
            
            $this->news_parser_msn_lib->insert_news( $insert_data );
    
            flush(); $i++;
            sleep(5);
        }

    }
    
    function get_articles_url($scanUrl = false){
//        if( $this->single_work( 2, 'parse_articles_url') == false ) exit('The work temporary Lock');
        
        $this->load->library('parser/articles_lib');
        
        if(!isset($scanUrl) OR !  is_array($scanUrl) )
        {
            $scanUrl    = $this->donor_m->getScanPageListUrl();
        }
        
        $this->donor_m->updScanUrlTime( $scanUrl['id'] );
        
//        $scanUrl['url']         = 'http://www.msn.com/ru-ru/money/markets';
//        $scanUrl['host']        = 'www.msn.com';
//        $scanUrl['cat_id']      = 12;
//        $scanUrl['donor_id']    = 1;
        
        echo '<pre>'.print_r( $scanUrl, 1 ).'</pre>';
        
        $this->articles_lib->setScanUrl( $scanUrl['url'] );
        $data = $this->articles_lib->getData( $scanUrl['host'] );
        
        echo '<pre>'.print_r( $data, 1 ).'</pre>'; 
        
        if( $data == null ) exit("No URLs to add");
        
        foreach( $data as $urlAr ){
            
            $stopURLsPattern = "#(www\.bing\.com/news/apiclick\.aspx\?ref)#"; 
            if( preg_match($stopURLsPattern, $urlAr['url']) )
            {
                echo "\n-- Bad URL -- {$urlAr['url']}<br />\n";
                continue;
            }
            
            $this->parser_m->add_to_scanlist( $urlAr['url'], $scanUrl['cat_id'], $scanUrl['donor_id'], $urlAr['img'] );
        }
    }
    
    function get_articles_url_all(){
        set_time_limit(180);
        $scanUrlAr = $this->donor_m->getAllScanPageListUrl();
        
        if(count($scanUrlAr)<1)
        {
            echo "<br />\n!Нет URL для сканирования\n<br />";
        }
        else 
        {
            foreach ($scanUrlAr as $scanUrl)
            {
                $this->get_articles_url($scanUrl);
                flush();
                sleep(5);
            }
        }
    }
      
    function _get_old_articles_url(){
        set_time_limit(1800);
        if( $_SERVER['REMOTE_ADDR'] != '109.86.165.207' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) exit('IP Not Allow');
        
        $this->load->library('parser/articles_lib');
        
        $countPage = 5; 
        
        for($page=2; $page <= $countPage; $page++){
            
//            if( $page == 1 ){
//            $scanUrl['url']         = 'http://compulenta.computerra.ru/tehnika/computers/';}
//            else{
//            $scanUrl['url']         = 'http://compulenta.computerra.ru/tehnika/computers/?PAGEN_1='.$page;}
//            $scanUrl['host']        = 'compulenta.computerra.ru';
//            $scanUrl['donor_id']    = 8;
//            $scanUrl['cat_id']      = 20;
            
//            if( $page == 1 ){
//                $scanUrl['url']     = 'http://habrahabr.ru/hub/hardware/';
//            }
//            else{
//                $scanUrl['url']     = 'http://habrahabr.ru/hub/hardware/page'.$page.'/';
//            }
//            $scanUrl['host']        = 'habrahabr.ru';
//            $scanUrl['donor_id']    = 9;
//            $scanUrl['cat_id']      = 20;
            
//            if( $page == 1 ){
//                $scanUrl['url']     = 'http://itc.ua/tag/notebook/';
//            }
//            else{
//                $scanUrl['url']     = 'http://itc.ua/tag/notebook/page/'.$page.'/';
//            }
//            $scanUrl['host']        = 'itc.ua';
//            $scanUrl['donor_id']    = 10;
//            $scanUrl['cat_id']      = 20;
            
//            if( $page == 1 ){
//                $scanUrl['url']     = 'http://4pda.ru/news/';
//            }
//            else{
//                $scanUrl['url']     = 'http://4pda.ru/news/page/'.$page.'/';
//            }
//            $scanUrl['host']        = '4pda.ru';
//            $scanUrl['donor_id']    = 12;
//            $scanUrl['cat_id']      = 22;
            
            
//            if( $page == 1 ){
//                $scanUrl['url']     = 'http://supreme2.ru/category/cameras/';
//            }
//            else{
//                $scanUrl['url']     = 'http://supreme2.ru/category/cameras/page/'.$page.'/';
//            }
//            $scanUrl['host']        = 'supreme2.ru';
//            $scanUrl['donor_id']    = 14;
//            $scanUrl['cat_id']      = 23;  
            
            
            if( $page == 1 ){
                $scanUrl['url']     = 'http://hochu.ua/cat-sex/we-and-men/';
            }
            else{
                $scanUrl['url']     = 'http://hochu.ua/cat-sex/we-and-men/order-date/period-all/page-'.$page.'/';
            }
            $scanUrl['host']        = 'hochu.ua';
            $scanUrl['donor_id']    = 16;
            $scanUrl['cat_id']      = 46;
            
            
//            if( $page == 1 ){
//                $scanUrl['url']     = 'http://www.goodhouse.ru/style/trend/';
//            }
//            else{
//                $scanUrl['url']     = 'http://www.goodhouse.ru/style/trend/?PAGEN_1='.$page;
//            }
//            $scanUrl['host']        = 'www.goodhouse.ru';
//            $scanUrl['donor_id']    = 17;
//            $scanUrl['cat_id']      = 25; 
            
            
//            if( $page == 1 ){
//                $scanUrl['url']     = 'http://lady.tsn.ua/dom_i_deti/deti';
//            }
//            else{
//                $scanUrl['url']     = 'http://lady.tsn.ua/dom_i_deti/deti/?page='.$page;
//            }
//            $scanUrl['host']        = 'lady.tsn.ua';
//            $scanUrl['donor_id']    = 18;
//            $scanUrl['cat_id']      = 38;

            $this->articles_lib->setScanUrl( $scanUrl['url'] );
            $data = $this->articles_lib->getData( $scanUrl['host'] );

            echo '<br />-------Page '.$page.' scaning-------<br />';
            echo '<pre>'.print_r( $data, 1 ).'</pre>';
            flush();

            if( $data == null ) exit("No URLs to add");

            foreach( $data as $urlAr ){
                $this->parser_m->add_to_scanlist( $urlAr['url'], $scanUrl['cat_id'], $scanUrl['donor_id'], $urlAr['img'] );
            }
            sleep(5);
        }
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
    
    private function _tmp_clean_doubles(){
        exit('lock method');
        set_time_limit(1800);
        $doubleIdAr = array();        
        $sql     = "SELECT `id`, `title` FROM `article` ";

        $query = $this->db->query($sql);

        foreach($query->result_array() as $row){
            $allAricles[$row['id']] = $row;
        }

        foreach( $allAricles as $artticleData ){
            
            if( in_array($artticleData['id'], $doubleIdAr) ) continue;
            
            $artticleData['title'] = $this->db->escape_str($artticleData['title']);
            $sql2    = "SELECT `id`, `title` FROM `article` WHERE `title` =  '{$artticleData['title']}' AND `id` != '{$artticleData['id']}' ";
            
            echo $artticleData['id'].'-'.$artticleData['title'].'<br />';
            
            $query2 = $this->db->query( $sql2 );

            foreach($query2->result_array() as $row){
                $doubleIdAr[] = $row['id'];
                echo '---Удаление--'.$row['id'].'--'.$row['title'].'<br />';
                $query3 = $this->db->query("SELECT `main_img` FROM `article` WHERE `id` = '{$row['id']}' ");
                $rowImg     = $query3->row_array();
                $mainImg    = $rowImg['main_img'];
                if( !empty($mainImg) ){
                    if( is_file( 'upload/images/medium/'.$mainImg ) )
                        if( unlink('upload/images/medium/'.$mainImg) ) echo '---- medium img удалена<br />';
                    if( is_file( 'upload/images/real/'.$mainImg ) )
                        if( unlink('upload/images/real/'.$mainImg) ) echo '---- real img удалена<br />';
                    if( is_file( 'upload/images/small/'.$mainImg ) )
                        if( unlink('upload/images/small/'.$mainImg) ) echo '---- small img удалена<br />';
                }
                if( $this->db->query("DELETE FROM `article`  WHERE `id` = '{$row['id']}' ") ) echo '----- новость удалена<br />';
                if( $this->db->query("DELETE FROM `shingles` WHERE `article_id` = '{$row['id']}' ") )echo '----- шинглы удалены<br />';
            }
        }
    }
    
    function _upd_news( $cnt_news = 1 ){
        header("Content-type:text/html;Charset=utf-8");
        
//        $parse_list = $this->parser_m->get_news_url_to_parse( $cnt_news );
        $sql = "SELECT "
                . "`article`.`id`, `scan_url`.`url`, `scan_url`.`main_img_url`"
                . " FROM  `article`, `scan_url` "
                . "WHERE "
                . "`article`.donor_id = 9 "
                . "AND"
                . " `article`.`date` >= '2014-07-30' "
                . "AND "
                . " `article`.`date` < '2014-09-27' "
                . "AND"
                . " `scan_url`.`id` = `article`.`scan_url_id` "
                . "AND"
                . " `article`.`views` = 0 "
                . "ORDER BY `article`.`date` DESC "
                . "LIMIT {$cnt_news}";
        
        $query = $this->db->query($sql);
        
        if( $query->num_rows() < 1 ) return FALSE;
        
        $parse_list = array();
        foreach( $query->result_array() as $row ){
            $parse_list[] = $row;
        }
        
        
        
        if( $parse_list == null ){  echo "ERROR Отсутствуют URL для сканирования"; return; }
        
        $i=1;
        foreach( $parse_list as $news_ar ){
            $this->db->query("UPDATE `article` SET `views`=1 WHERE `id`='{$news_ar['id']}' LIMIT 1");
            
            $news_ar['host'] = 'habrahabr.ru';
            
            $html = $this->news_parser_lib->down_with_curl( $news_ar['url'] );
            
            $insert_data = $this->parse_page_lib->get_data( $html, $news_ar );
            
            if( is_array($insert_data) == false ) continue;
            $insert_data['url']             = $news_ar['url'];
            
            
            if( !empty($news_ar['main_img_url']) && empty($insert_data['img']) ){ 
                $insert_data['img']         = $news_ar['main_img_url'];
            }    
                
            
            echo "<br />\n$i - <i>".$news_ar['url']."</i><br />\n";
            
//            echo '<pre>'.print_r($news_ar,1).'</pre>';
//            echo '<pre>'.print_r($insert_data,1).'</pre>';

            $this->news_parser_lib->tmp_upd_news( $insert_data,  $news_ar['id']);
    
            flush(); $i++;
        }    
    }
    
    function _del_articles( $cnt = 10 ){
        set_time_limit(300);
        header("Content-type:text/plain;Charset=utf-8");
        
        if( $_SERVER['REMOTE_ADDR'] != '109.86.165.207' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) exit('IP Not Allow');
        
        $cnt = (int) $cnt;
        
        $sql = "SELECT `id`, `donor_id`, `scan_url_id`, `title`, `text`, `main_img` "
                . "FROM `article` "
                . "WHERE "
                . "`donor_id` = '16' "
                . "AND "
                . "`date` > '2014-11-01 00:00:00' "
                . "LIMIT {$cnt} ";
        
        $query = $this->db->query( $sql );
        
        if( $query->num_rows() < 1 ) exit("No Articles to Delete ");
        
        $i = 1;
        foreach( $query->result_array() as $row ){
            
            echo "ID: ".$row['id']." - ".$row['title']." \n";
            
            // Delete Main Image 
            echo "\nDelete Main Image \n";
            if( !empty($row['main_img']) ){
                $this->delAllImg( $row['main_img'] );
            }
            
            // Delete Images from Text 
            echo "\nDelete All Images \n";
            $imgDatePathNameAr = $this->getImgNameFromTxt( $row['text'] );
            if( $imgDatePathNameAr ){
                foreach($imgDatePathNameAr as $imgDatePathName ){
                    $this->delAllImg( $imgDatePathName );
                }
            } 
            
            // Delete `scan_url`
            if( $this->db->query("DELETE FROM `scan_url` WHERE `id` = '{$row['scan_url_id']}' LIMIT 1 ") ){
                echo "\n\t Scan URL Deleted \n";
            }
            // Delete `shingles`
            if( $this->db->query("DELETE FROM `shingles` WHERE `article_id` = '{$row['id']}' ") ){
                echo "\n\t Shingles Deleted \n";
            }
            
            if( $this->db->query("DELETE FROM `article` WHERE `id` = '{$row['id']}' LIMIT 1 ") ){
                echo "\n\t Article Deleted \n";
            }
            
            $i++;
            echo "\n{$i} ----------------------------------------------------------------------- \n\n\n";
            flush();
        }
    }
    
    private function delAllImg( $imgDatePathName ){
        echo "\t Удаление: ".$imgDatePathName."\n";
        
        $imgFolderAr = array('small','medium','real');
        
        foreach( $imgFolderAr as $imgFolder ){
            
            $filePathName = 'upload/images/'.$imgFolder.'/'.$imgDatePathName;
            if( is_file($filePathName) ){
                if( unlink($filePathName) ){
                    echo "\t\t File was deleted: ".$filePathName."\n";
                }
                else{
                    echo "\t\t Delete ERROR: ".$filePathName."\n";
                }
            }
            
        }
    }
    
    private function getImgNameFromTxt( $text ){ //return 'yyyy/mm/dd/img_name.xxx'
        $pattern = "#src=\"/upload/images/[a-z]+/([\S]*?\.[a-z]{3,4})\"#i";
        
        preg_match_all($pattern, $text, $matches);
        
        if( isset($matches[1]) && count($matches[1]) >= 1 ){
            return $matches[1];
        }
        else{
            return FALSE;
        }
    }
    
    
}
