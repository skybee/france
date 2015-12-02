<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donor_admin_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    function add_domain( $data ){
        
        $query  = $this->db->query("SELECT `id` FROM `donor_domain` WHERE `name` = '{$data['name']}' ");
        if( $query->num_rows() < 1 ) return '! Не найден ID домена';
        $row    = $query->row();
        $p_id   = $row->id;
        
        $sql = "INSERT INTO `donor_subdomain` "
                . "SET"
                . "`donor_domain_id`    = '{$p_id}',"
                . "`subname`            = '{$data['subname']}',"
                . "`hosting`            = '{$data['hosting']}',"
                . "`account`            = '{$data['account']}'";
        
                
        if( $this->db->query( $sql ) ) return 'Запись занесена';
        else return '! Ошибка добавления <pre>'.print_r($data,1).'</pre>';
    } 
    
    function getDomainList(){
        $sql = "SELECT "
                . "`donor_domain`.`id`, `donor_domain`.`name`, `donor_domain`.`cy`, `donor_domain`.`pr`, "
                . "`donor_subdomain`.`id` AS `sub_id`, `donor_subdomain`.`subname`, `donor_subdomain`.`hosting`, `donor_subdomain`.`account` "
                . "FROM `donor_domain` "
                . "LEFT JOIN `donor_subdomain` "
                . "ON `donor_domain`.`id` = `donor_subdomain`.`donor_domain_id` "
                . "ORDER BY `donor_domain`.`cy` DESC, `donor_domain`.`pr` DESC";
        
        $query = $this->db->query( $sql );
        
        $result = array();
        foreach( $query->result_array() as $row ){
            $result[ $row['id'] ]['id']     = $row['id'];
            $result[ $row['id'] ]['name']   = $row['name'];
            $result[ $row['id'] ]['cy']     = $row['cy'];
            $result[ $row['id'] ]['pr']     = $row['pr'];
            
            if( $row['sub_id'] != NULL ){
                $result[ $row['id'] ]['sub'][ $row['sub_id'] ]['id']        = $row['sub_id'];
                $result[ $row['id'] ]['sub'][ $row['sub_id'] ]['subname']   = $row['subname'];
                $result[ $row['id'] ]['sub'][ $row['sub_id'] ]['hosting']   = $row['hosting'];
                $result[ $row['id'] ]['sub'][ $row['sub_id'] ]['account']   = $row['account'];
            }
        }
        
        return $result;
    }
}