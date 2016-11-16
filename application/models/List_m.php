<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    function get_cat( $pid ){
        $query = $this->db->query("SELECT * FROM `category` WHERE `parent_id`='{$pid}'  ORDER BY `sort`" );
        
        $result_ar = array();
        
        foreach( $query->result_array() as $row ){
            $result_ar[] = $row;
        }
        
        return $result_ar;
    }
    
    function get_sCat_from_name( $catName ){
        
        if( empty($catName) ) $catName = 'news';
        
        $cacheName = $_SERVER['HTTP_HOST'].'_'.'s_cat_'.$catName;
        
        if( !$result_ar = $this->cache->file->get($cacheName) ){
            $query = $this->db->query(" SELECT `category`.* "
                                        . " FROM `category`, `category` AS `p_cat` "
                                        . " WHERE "
                                        . " `p_cat`.`url_name`='{$catName}'  "
                                        . " AND "
                                        . " `category`.`parent_id` = `p_cat`.id "
                                        . " ORDER BY `category`.`sort` " );

            $result_ar = array();

            foreach( $query->result_array() as $row ){
                if( !empty($row['sub_cat_id']) ){
                    $row['sub_cat_list'] = $this->get_cat( $row['id'] );
                }
                $result_ar[] = $row;
            }
            
            $this->cache->file->save($cacheName, $result_ar, 3600*24 );
        }
        
        return $result_ar;
    }
    
    function get_footer_cat_link(){
        $cacheName = $_SERVER['HTTP_HOST'].'_'.'footer_cat';
        
        if( !$allCatAr = $this->cache->file->get($cacheName) ){
            $mainCatAr = $this->get_cat(0);

            if( count($mainCatAr) < 1 ) return NULL;

            $allCatAr = array();
            foreach( $mainCatAr as $mainCat ){
                $sCat = $this->get_cat( $mainCat['id'] );
                if( count($sCat) < 1 ) continue;
                $mainCat['s_cat'] = $sCat;

                $allCatAr[] = $mainCat;
            }
            $this->cache->file->save($cacheName, $allCatAr, $this->cacheTime['footerCat'] * 60 );
        }
        
        return $allCatAr;
    }
    
    function getMenuListForMobile(){
        $cacheName = $_SERVER['HTTP_HOST'].'_'.'mobile_menu';
        
        if( !$mainList = $this->cache->file->get($cacheName) ){
            $mainList = $this->get_cat(0);

            $cntMainList = count($mainList);

            for($i=0;$i<$cntMainList;$i++){
                $mainList[$i]['sub_menu'] = $this->get_sCat_from_name($mainList[$i]['url_name']) ;
            }
            
            $this->cache->file->save($cacheName, $mainList, 3600*24 );
        }
        
        return $mainList;
    }
} 