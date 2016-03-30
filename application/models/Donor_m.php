<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Donor_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    
    function getDonorIdFromHost( $host ){
        $sql = "SELECT `id` FROM `donor` WHERE `host` LIKE '%{$host}%' LIMIT 1 ";
        
        $query = $this->db->query( $sql );
        
        $row = $query->row();
        return $row->id;
    }
    
    function getScanPageListUrl(){
        $query = $this->db->query(
                "   SELECT articles_donor_url.*, donor.host "
                . " FROM `articles_donor_url`, `donor` "
                . " WHERE donor.id = articles_donor_url.donor_id "
                . " ORDER BY `scan_time` LIMIT 1"
                );
        
        return $query->row_array();
    }
    
    function getAllScanPageListUrl(){
        
        $sql = "   SELECT `articles_donor_url`.*, `donor`.`host` "
                . " FROM `articles_donor_url`, `donor` "
                . " WHERE `donor`.`id` = `articles_donor_url`.`donor_id` "
                . " ORDER BY `scan_sort` ASC, `scan_time` ASC ";
        
        $query = $this->db->query($sql);
        
        $resultAr = array();
        
        if( $query->num_rows() >= 1)
        {
            foreach ($query->result_array() as $row)
            {
                $resultAr[] = $row;
            }
        }
        
        return $resultAr;
    }
    
    function updScanUrlTime( $urlId ){
        $this->db->query("UPDATE `articles_donor_url` SET `scan_time` = CURRENT_TIMESTAMP WHERE `id` = '{$urlId}' LIMIT 1 ");
    }
}