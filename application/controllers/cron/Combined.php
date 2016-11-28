<?php   

class Combined extends CI_Controller
{
    private $hosts;
    
    function __construct() {
        parent::__construct();
        
        $this->load->config('multidomaine');
        $this->load->library('multidomaine_lib');
        
        $this->hosts = $this->multidomaine_lib->getHostsList();
    }
    
    function index(){ echo 'index'; }
    
    
    function get_articles_url_all(){
        set_time_limit(3600);
        $url = "http://-host-/parser/main/get_articles_url_all/";
        
        $this->each_http_query($url);
        
        echo date("H:i:s")."- OK - ".__METHOD__;
    }
    
    function parse_news($cntNews=10){
        set_time_limit(600);
        $url = "http://-host-/parser/main/parse_news/{$cntNews}/";
        
        $this->each_http_query($url);
        
        echo date("H:i:s")."- OK - ".__METHOD__;
    }
    
    function yandex_xml($cntNews=10,$action='add'){
        set_time_limit(300);
        $url = "http://-host-/cron/serp_parse/yandex_xml/{$cntNews}/{$action}/";
        
        $this->each_http_query($url);
        
        echo date("H:i:s")."- OK - ".__METHOD__;
    }
    
    function upd_article_view(){
        set_time_limit(1800);
        $url = "http://-host-/cron/article_top/upd_article_view/";
        
        $this->each_http_query($url);
        
        echo date("H:i:s")."- OK - ".__METHOD__;
    }
    
    function parse_like_video($cntNews=10){
        set_time_limit(600);
        $url = "http://-host-/cron/youtube_like_parse/parse_like_video/{$cntNews}/";
        
        $this->each_http_query($url);
        
        echo date("H:i:s")."- OK - ".__METHOD__;
    }
    
    
    private function each_http_query($urlTpl){
        
        foreach ($this->hosts as $host){
            $url            = preg_replace("#-host-#i", $host, $urlTpl);
            
            echo date("H:i:s")."- <b>Request:</b> ".$url."<br />\n<br />\n";
            flush();
            
            $requestData    = $this->down_with_curl($url);
            $answerCode     = $requestData['http_data']['http_code']; 
            
            echo date("H:i:s")."- <b>HTTP Code:</b> {$answerCode} <br />\n<br />\n";
            echo date("H:i:s")."- <b>Answer:</b><br />\n<br />\n <pre>".$requestData['data']."</pre><br />\n<br />\n";
            flush();
        }
    }
    
    private function down_with_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko' );
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 600);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

	$content    = curl_exec($ch);
        $httpData   = curl_getinfo($ch);
	curl_close($ch);
        
        $returnAr['data']       = $content;
        $returnAr['http_data']  = $httpData;

        return $returnAr;
    }
    
}

