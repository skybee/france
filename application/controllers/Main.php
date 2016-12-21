<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->model('list_m');
        $this->load->model('article_m');
        $this->load->model('category_m');
        $this->load->helper('date_convert');
        $this->load->helper('doc_helper');
        $this->load->driver('cache');
        $this->load->config('category'); // -?
        $this->load->config('multidomaine');
        $this->load->library('multidomaine_lib');
        $this->load->library('cat_lib');
        $this->load->library('Express_news_lib');
        
        $this->catNameAr = $this->cat_lib->getCatFromUri();
        $this->catConfig = $this->cat_lib->getCatConfig();
        
        $this->cacheTime['footerCat']     = 180; //minutes
        
        $this->topSliderTxtLength       = 290;
        
        $this->multidomaine = $this->multidomaine_lib->getHostData();
        
        ///////// TMP /////////
        //$this->db->query("INSERT INTO `shingles` SET `hash`='{$_SERVER['HTTP_X_REAL_IP']}'");
    }

    function index(){ 
        $mainCatData = $this->category_m->get_cat_data_from_id(1);
        $this->catNameAr[0] = $mainCatData['url_name'];
        $this->main_page($mainCatData['url_name']);
    }

    function main_page($cat_name) {
        $this->output->cache( $this->catConfig['cache_time_main_page_m'] );

        $data_ar['main_cat_ar']         = $this->article_m->get_cat_data_from_url_name($cat_name);
        
        if(!isset($data_ar['main_cat_ar']['id'])){
            show_404(); exit();
        }
        
//        if($cat_name == 'news')
//        {
            $data_ar['express_news'] = $this->express_news_lib->get_news();
//        }
        
        
        $data_ar['main_menu_list']      = $this->list_m->get_cat(0);
        $data_ar['second_menu_list']    = $this->list_m->get_sCat_from_name($this->catNameAr[0]);
        $data_ar['footer_menu_list']    = $this->list_m->get_footer_cat_link();
        $mobile_menu_list               = $this->list_m->getMenuListForMobile();
        $data_ar['mainpage_cat_list']   = $this->article_m->get_mainpage_cat_news($data_ar['second_menu_list']); // 9.5 sec.
        $data_ar['meta']['title']       = $data_ar['main_cat_ar']['title'];
        
        $top_slider['articles']         = $this->article_m->get_top_slider_data($data_ar['main_cat_ar']['id'], 8, $this->catConfig['top_news_time_h'], $this->topSliderTxtLength, true, true); // 1.5 sec.
        $right['right_top']             = $this->article_m->get_top_slider_data($data_ar['main_cat_ar']['id'], 8, $this->catConfig['right_top_news_time_h'], $this->topSliderTxtLength, true, true, 'right_top');
        $top_slider['main_cat_url']     = $data_ar['main_cat_ar']['url_name'];
        $right['last_news']             = $this->article_m->get_last_left_news( $data_ar['main_cat_ar']['id'], 50 ); // 1.5 sec.

        $tpl_ar = $data_ar; //== !!! tmp    
        $tpl_ar['content']  = $this->load->view('component/main_latest_v', $data_ar, true);
        $tpl_ar['content'] .= $this->load->view('component/cat_listing_v', $data_ar, true);
        $tpl_ar['content'] .= $this->load->view('component/main_other_news_v', $data_ar, true);// .'<div>'.$msg.'</div>';
        
        $tpl_ar['right']        = $this->load->view('component/right_last_news_v', $right, true);
        $tpl_ar['top_slider']   = $this->load->view('component/slider_top_v', $top_slider, true);
        $tpl_ar['mobile_menu']  = $this->load->view('component/mobile_menu_v', array('mobile_menu_list'=>$mobile_menu_list), true);

        $this->load->view('main_v', $tpl_ar);
    }

    function document($url_id_name) {
        
        preg_match("#-(\d+)-(.+)#i", $url_id_name, $url_id_name_ar); //зазбор URL_name
        $doc_id = $url_id_name_ar[1];
        $doc_urlname = $url_id_name_ar[2];

        $data_ar['doc_data']            = $this->article_m->get_doc_data($doc_id);
        if (!$data_ar['doc_data']){
            $cat_url = preg_replace("#/-\d+-\S+?/$#i", '/', $_SERVER['REQUEST_URI']);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$cat_url}#404");
            exit();
        }

        $right['serp_list'] = serpDataFromJson($data_ar['doc_data']['serp_object']);
        unset($data_ar['doc_data']['serp_object']);
        
        $true_url = '/'.$data_ar['doc_data']['cat_full_uri'].'-'.$data_ar['doc_data']['id'].'-'.$data_ar['doc_data']['url_name'].'/';
        
        if( $true_url != $_SERVER['REQUEST_URI'] ){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$true_url);
            exit();
        }
        
        $data_ar['cat_ar']              = $this->category_m->get_cat_data_from_id($data_ar['doc_data']['cat_id']);
        $data_ar['like_articles']       = $this->article_m->get_like_articles( $data_ar['doc_data']['id'], $data_ar['doc_data']['cat_id'] /*$data_ar['cat_ar']['parent_id']*/, $data_ar['doc_data']['title'], 8, $this->catConfig['like_news_day_d'], $data_ar['doc_data']['date'] );
        
        $data_ar['main_menu_list']      = $this->list_m->get_cat(0);
        $data_ar['second_menu_list']    = $this->list_m->get_sCat_from_name($this->catNameAr[0]);
        $data_ar['footer_menu_list']    = $this->list_m->get_footer_cat_link();
        $mobile_menu_list               = $this->list_m->getMenuListForMobile();
        $data_ar['meta']['title']       = $data_ar['cat_ar']['name'].': '.$data_ar['doc_data']['title'].' - '.$this->multidomaine['site_name_str'];
        $data_ar['donor_rel']           = ' rel="nofollow" '; #botRelNofollow();

        //вставка like_articles[0] в текст
        $data_ar['doc_data']['text']    = insertLikeArtInTxt($data_ar['doc_data']['text'], $data_ar['like_articles'], $right['serp_list']);
        $data_ar['doc_data']['text']    = addResponsiveVideoTag($data_ar['doc_data']['text']);

        $data_ar['like_video']          = $this->article_m->get_like_video($data_ar['doc_data']['id'],2);
        
        $top_slider['articles']         = $this->article_m->get_top_slider_data( $data_ar['cat_ar']['id'], 8, $this->catConfig['top_news_time_h'], $this->topSliderTxtLength, true, false);
        $right['right_top']             = $this->article_m->get_top_slider_data( $data_ar['cat_ar']['parent_id'], 8, $this->catConfig['right_top_news_time_h'], $this->topSliderTxtLength, true, true, 'right_top');
        $right['last_news']             = $this->article_m->get_last_left_news( $data_ar['cat_ar']['parent_id'], 20 );
        
        if($_SERVER['HTTP_HOST'] !== $this->multidomaine['host']){ //Aliases Canonical
            $canonicalUrl                   = 'http://'.$this->multidomaine['host'].$_SERVER['REQUEST_URI'];
            $data_ar['meta']['canonical']   = '<link rel="canonical" href="'.$canonicalUrl.'" />'."\n";
        }
        
        $tpl_ar                 = $data_ar; //== !!! tmp
        $tpl_ar['content']      = $this->load->view('page/doc_v', $data_ar, true); // .'<div>'.$msg.'</div>';
        $tpl_ar['top_slider']   = $this->load->view('component/slider_top_v', $top_slider, true);
        $tpl_ar['right']        = $this->load->view('component/right_last_news_v', $right, true);
        $tpl_ar['meta']['og']   = $this->load->view('component/meta_og_v', $data_ar['doc_data'], true);
        $tpl_ar['mobile_menu']  = $this->load->view('component/mobile_menu_v', array('mobile_menu_list'=>$mobile_menu_list), true);
        $tpl_ar['out_popup']    = $this->load->view('component/out_popup_v', $data_ar['like_articles'], true);

        $this->load->view('main_v', $tpl_ar);
    }

    function cat_list($cat_name, $page) {
        
        $page = (int) $page;
        if (!$page) $page = 1;

        $data_ar['cat_ar'] = $this->category_m->get_cat_data_from_url( $cat_name );
        
        if( !isset($data_ar['cat_ar']['id']) ){
            show_404(); exit();
        }
        
//        if($data_ar['cat_ar']['parent_id'] === 0)
//        {
//            return $this->main_page($data_ar['url_name']);
//        }
        
        if($page > 100) { // temp redirect 
            header("Location: /{$data_ar['cat_ar']['full_uri']}", true, 302);
            exit();
        }
        
        $data_ar['news_page_list']      = $this->article_m->get_page_list($data_ar['cat_ar']['id'], $page, 15, 250 );
        $data_ar['pager_ar']            = $this->article_m->get_pager_ar( $data_ar['cat_ar']['id'], $page, 15, 4);
        $data_ar['page_nmbr']           = $page;
        
        if (!$data_ar['news_page_list'])
        {
            show_404();
        }
        $data_ar['main_menu_list']      = $this->list_m->get_cat(0);
        $data_ar['second_menu_list']    = $this->list_m->get_sCat_from_name($this->catNameAr[0]);
        $data_ar['footer_menu_list']    = $this->list_m->get_footer_cat_link();
        $mobile_menu_list               = $this->list_m->getMenuListForMobile();
        $data_ar['meta']['title']       = $data_ar['cat_ar']['title']; 
        if( $page > 1){
            $data_ar['meta']['title']  .= ' - '.$this->multidomaine['page_str'].' '.$page;
            $data_ar['meta']['noindex'] = true;
        }

        $top_slider['articles']         = $this->article_m->get_top_slider_data( $data_ar['cat_ar']['id'], 8, $this->catConfig['top_news_time_h'], $this->topSliderTxtLength, true, false);
        $right['right_top']             = $this->article_m->get_top_slider_data( $data_ar['cat_ar']['parent_id'], 8, $this->catConfig['right_top_news_time_h'], $this->topSliderTxtLength, true, true, 'right_top');
        $right['last_news']             = $this->article_m->get_last_left_news( $data_ar['cat_ar']['parent_id'], 50 );
        
        $tpl_ar                 = $data_ar; //== !!! tmp
        $tpl_ar['content']      = $this->load->view('page/cat_list_v', $data_ar, true);
        $tpl_ar['top_slider']   = $this->load->view('component/slider_top_v', $top_slider, true);
        $tpl_ar['right']        = $this->load->view('component/right_last_news_v', $right, true);
        $tpl_ar['mobile_menu']  = $this->load->view('component/mobile_menu_v', array('mobile_menu_list'=>$mobile_menu_list), true);

        $this->load->view('main_v', $tpl_ar);
    }
    
    function search( $page=1 ){
        $page = (int) $page; if (!$page) $page = 1;
        
        $searchStr   = $_GET['q'];
        $searchStr   = preg_replace("#[^a-z а-яё]#iu", ' ', $searchStr);
        
//        $data_ar['main_cat_ar']         = $this->article_m->get_cat_data_from_url_name($cat_name);
        #$data_ar['news_page_list']      = $this->article_m->get_search_page_list($searchStr, $page, 15 );
        #$data_ar['pager_ar']            = $this->article_m->get_search_pager_ar( $searchStr, $page, 15, 4);
        #$data_ar['page_nmbr']           = $page;
        
        #if (!$data_ar['news_page_list'])
        #    $data_ar['news_page_list'] = NULL; #show_404();
        $data_ar['main_menu_list']      = $this->list_m->get_cat(0);
        $data_ar['second_menu_list']    = $this->list_m->get_cat(1);
        $data_ar['footer_menu_list']    = $this->list_m->get_footer_cat_link();
        $mobile_menu_list               = $this->list_m->getMenuListForMobile();
        $data_ar['meta']['title']       = 'Поиск: &laquo;'.$searchStr.'&raquo;  - '.$this->multidomaine['page_str'].' '.$page;
        
        $top_slider['articles']         = $this->article_m->get_top_slider_data(1, 8, $this->catConfig['right_top_news_time_h'], $this->topSliderTxtLength, true, true); // 1.5 sec.
        $right['right_top']             = $this->article_m->get_top_slider_data(1, 8, $this->catConfig['right_top_news_time_h'], $this->topSliderTxtLength, true, true, 'right_top');
        
        $right['last_news']              = $this->article_m->get_last_left_news( 1, 50 );
        
        //<Rename for cat list view>
        $data_ar['cat_ar']['p_name']    = 'Поиск временно отключен';
        $data_ar['cat_ar']['name']      = '';#$searchStr;
        $data_ar['search_url_str']      = str_replace(' ', '+', $searchStr);
        //</Rename for cat list view>
        
        $tpl_ar                 = $data_ar; //== !!! tmp
        $tpl_ar['content']      = $this->load->view('page/cat_list_v', $data_ar, true);
        $tpl_ar['top_slider']   = $this->load->view('component/slider_top_v', $top_slider, true);
        $tpl_ar['right']        = $this->load->view('component/right_last_news_v', $right, true);
        $tpl_ar['mobile_menu']  = $this->load->view('component/mobile_menu_v', array('mobile_menu_list'=>$mobile_menu_list), true);

        $this->load->view('main_v', $tpl_ar);
    }
    
    
    
    
    function _sitemap_link_page( $cat, $page = 1 ){
        $this->output->cache( 3600*24*10 );
        
        $query      = $this->db->query(" SELECT `sub_cat_id`, `name` FROM `category` WHERE `url_name` = '{$cat}' LIMIT 1 ");
        $row        = $query->row_array();
        $subCatId   = $row['sub_cat_id'];
        $catNmae    = $row['name'];
        
        if( empty($subCatId) ) exit("<h1>Cat Name Error</h1>");
        
        $cnt    = 150;
        $stop   = $page * $cnt;
        $start  = $stop - $cnt;
        
        $sql = "SELECT "
                . "`article`.`url_name`, `article`.`title`, `article`.`id`, `article`.`views`, "
                . "`category`.`full_uri` "
                . "FROM "
                . "`article`, `category` "
                . "WHERE "
                . "`article`.`cat_id` IN ({$subCatId}) "
                . "AND "
                . "`category`.`id` = `article`.`cat_id` "
                . "ORDER BY `article`.`views` DESC "
                . "LIMIT {$start}, {$cnt} ";
        
        $query = $this->db->query($sql);
        
        if( $query->num_rows() < 1 ) exit("<h1>No link</h1>\n".$sql);
        
        $html   = '<html><head><title>'.$catNmae.' '.$this->multidomaine['page_str'].' - '.$page.'</title></head><body>';
        
        foreach( $query->result_array() as $catData ){
            $html   .= $catData['views'].' - '.$catData['title']."<br />\n";
            $html   .= "<a href=\"/{$catData['full_uri']}-{$catData['id']}-{$catData['url_name']}/\" >".$catData['title'].'</a>'."<br /><br />\n\n";
        }
        
        $html  .= '</body></html>';
        
        $data['html'] = $html;
        
        $this->load->view('page/spe_link_v', $data );
    }

}