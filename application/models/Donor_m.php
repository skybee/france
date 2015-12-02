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
    
    function updScanUrlTime( $urlId ){
        $this->db->query("UPDATE `articles_donor_url` SET `scan_time` = CURRENT_TIMESTAMP WHERE `id` = '{$urlId}' LIMIT 1 ");
    }
}