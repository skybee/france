<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Category_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    function get_cat_data_from_url( $cat_url ){
        $cat_url    = $this->db->escape_str($cat_url);
        $sql    = "SELECT `category`.*, `cat2`.`name` AS 'p_name' "
                . "FROM "
                . "`category`, `category` AS `cat2` "
                . "WHERE "
                . "`category`.`url_name` = '{$cat_url}' "
                . "AND "
                . "`cat2`.`id` = `category`.`parent_id`"
                . "LIMIT 1";
        
        $query = $this->db->query($sql);
        
        return $query->row_array();
    }
    
    function get_cat_data_from_id( $cat_id ){
        $sql = "SELECT * FROM `category` WHERE `id` = '{$cat_id}' LIMIT 1 ";
        
        $query = $this->db->query($sql);
        
        return $query->row_array();
    }
    
    function get_cat_url( $last_cat_id ){ //отдает вычесленный uri категории в лереве
        $sql    = "SELECT `url_name`, `parent_id` FROM `category` WHERE `id` = '{$last_cat_id}' ";
        $query  = $this->db->query( $sql );
        $row    = $query->row_array();
        $url    = $row['url_name'].'/';
                
        if( $row['parent_id'] != 0 ){
            $url = $this->get_cat_url( $row['parent_id'] ).$url;
        }
        
        return $url;
    }
    
    function change_cat_full_uri($cat_id){
        $cat_url = $this->get_cat_url( $cat_id );
        
        $sql = "UPDATE `category` SET `full_uri`='{$cat_url}' WHERE `id`='{$cat_id}' ";
        
        if( !$this->db->query($sql) )
            return FALSE;
        
        return $cat_url;
    }
    
    function getCatTimeSet($url_cat_name)
    {
        $sql = "SELECT `category_timeset`.* "
                . "FROM "
                . "`category`, `category_timeset` "
                . "WHERE "
                . "`category_timeset`.`id` = `category`.`timeset_tpl_id`"
                . "AND "
                . "`category`.`url_name` = '{$url_cat_name}' "
                . "LIMIT 1";
                
        $query = $this->db->query($sql);
        
        if($query->num_rows()<1)
        {
            return false;
        }
        else
        {
            return $query->row_array();
        }
    }
    
    function getDefaultTimeSet()
    {
        $sql = "SELECT * FROM `category_timeset` WHERE `name` = 'default' LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
        
}