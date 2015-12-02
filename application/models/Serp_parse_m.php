<?php


class Serp_parse_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getArticles($cnt = 10)
    {
        $sql = "SELECT `article`.`id`, `article`.`title` "
            . "FROM `article` "
            . "LEFT JOIN  `article_like_serp` ON  `article`.`id` =  `article_like_serp`.`article_id` "
            . "WHERE `article_like_serp`.`article_id` IS NULL "
            . "ORDER BY `id` "
            . "LIMIT {$cnt} ";

        $query = $this->db->query($sql);

        if( $query->num_rows() < 1 ) {
            return false;
        }

        $result = array();
        foreach ($query->result_array() as $row) {
            $result[] = $row;
        }

        return $result;
    }
    
    function getSerpList($cnt = 10)
    {
        $sql = "SELECT `article_like_serp`.`article_id` AS 'id', `article`.`title` "
                . "FROM `article_like_serp` "
                . "LEFT JOIN `article` ON `article_like_serp`.`article_id` = `article`.`id` "
                . "ORDER BY `serp_update` ASC "
                . "LIMIT {$cnt}";
        
        $query = $this->db->query($sql);

        if( $query->num_rows() < 1 ) {
            return false;
        }

        $result = array();
        foreach ($query->result_array() as $row) {
            $result[] = $row;
        }

        return $result;
    }

    function updateSerpData($id, $jsonStr)
    {
        $jsonStr = $this->db->escape_str($jsonStr);

        $sql = "UPDATE `article_like_serp` SET `serp_object`='{$jsonStr}', `serp_update` = NOW() WHERE `article_id` = '{$id}' LIMIT 1 ";

        if( $this->db->query($sql) ){
            return true;
        }
        else{
            return false;
        }
    }
    
    function addSerpData($id, $jsonStr)
    {
        $jsonStr = $this->db->escape_str($jsonStr);
        
        $sql = "INSERT IGNORE INTO `article_like_serp` SET `article_id`='{$id}', `serp_object`='{$jsonStr}', `serp_update` = NOW() ";
        
        if( $this->db->query($sql) ){
            return true;
        }
        else{
            return false;
        }
    }
}
