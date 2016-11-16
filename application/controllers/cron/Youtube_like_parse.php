<?php


class Youtube_like_parse extends CI_Controller
{
    function __construct() {
        parent::__construct();
        
        $this->load->database();
        set_time_limit(120);
    }
    
    
    function parse_like_video($cnt=1)
    {
        $cnt = (int) $cnt; 
        if($cnt<1) $cnt = 1;
        
        $articlesData   = $this->getNewsIdWithoutVideo($cnt);
        
        if(!$articlesData)
        {
            echo 'No Articles';
            flush();
            return;
        }
        
        foreach ($articlesData as $artData)
        {
            $likeVideoAr    = $this->getLikeVideo($artData['title']);
            
            if(count($likeVideoAr)<1) 
            {
                $this->insertVideoData($artData['id'], array('video_id'=>'none','title'=>'','description'=>''));
                continue;
            }
            
            foreach ($likeVideoAr as $ytVideoData)
            {
                $this->insertVideoData($artData['id'], $ytVideoData);
            }
            sleep(1);
        }
        
        
        
        
        echo '<pre>'.print_r($articlesData,1).'</pre>';
        flush();
    }
    
    private function getNewsIdWithoutVideo($cnt_articles = 10)
    {
        $cnt_articles = (int) $cnt_articles;
        
        $sql = "SELECT `article`.`id`,`article`.`title` "
                . "FROM `article` "
                . "LEFT OUTER JOIN  `youtube_like` "
                . "ON  `article`.`id` =  `youtube_like`.`article_id` "
                . "WHERE  `youtube_like`.`id` IS NULL  "
                . "ORDER BY `article`.`id` DESC "
                . "LIMIT {$cnt_articles} ";   
                
        $query = $this->db->query($sql);
        
        if($query->num_rows() < 1)
        {
            return false;
        }
        
        $result_ar = array();
        
        foreach ($query->result_array() as $row)
        {
            $result_ar[] = $row;
        }
        
        return $result_ar;
    }
    
    private function getLikeVideo($search)
    {
        //$search = "танк аэропорт донецк"; // Search Query
        $api    = "AIzaSyAa1pI7eAJpbs7sxL6L2A8DhgyCDNudYWM"; // YouTube Developer API Key
        $link   = "https://www.googleapis.com/youtube/v3/search?q=".urlencode($search). "&part=snippet&type=video&maxResults=2&key=". $api;
        $video  = file_get_contents($link);
        $video  = json_decode($video, true);

        $result_ar = array();
        
        $i=0;
        foreach ($video['items'] as $data){
            
            if(isset($data['id']['videoId']) && !empty($data['id']['videoId']))
            {
                $result_ar[$i]['title']         = $data['snippet']['title'];
                $result_ar[$i]['description']   = $data['snippet']['description'];
                $result_ar[$i]['video_id']      = $data['id']['videoId'];
                $i++;
            }
        }
        
        return $result_ar;
    }
    
    private function insertVideoData($articleId,$videoData)
    {
        $sql = "INSERT INTO `youtube_like` "
                . "SET "
                . "`article_id`     = '{$articleId}', "
                . "`video_id`       = '".$this->db->escape_str($videoData['video_id'])."', "
                . "`title`          = '".$this->db->escape_str($videoData['title'])."', "
                . "`description`    = '".$this->db->escape_str($videoData['description'])."' ";
                
        if($this->db->query($sql))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
}