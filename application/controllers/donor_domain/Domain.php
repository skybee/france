<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

exit();

set_time_limit(3600*5);



class Domain extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->library('donor_domain/parse_lib');
        $this->load->library('donor_domain/idna_convert_lib');
        $this->load->helper('parser/simple_html_dom_helper');
        $this->load->database();
    }
    
    function index( $domain = '' ){ 
        header("Content-type:text/plain;Charset=utf-8");
        echo $this->whois($domain);
    }
    
    function yandexGetCY($url){ 
        $cy = unserialize( file_get_contents( rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/application/controllers/donor_domain/cy2.txt' ) );
        $handle = fopen("http://yandex.ru/cycounter?$url", "rb"); 
        $contents = ''; 
        while (!feof($handle)) { 
        $contents .= fread($handle, 8192); 
        } 
        fclose($handle); 
        $hash = md5($contents);     
        if(array_key_exists($hash,$cy)){ 
            return $cy[$hash]; 
        } 
        return "none"; 
    } 
    
    function whois($domain){
        $domain = trim( $domain );
        if( !preg_match("#\.([^\.]+)$#i", $domain, $zove_ar ) ) exit('Domain is empty - '.$domain) ;
        $zone = strtolower( $zove_ar[1] );
        
        $whois_service_ar = array(
            'ua'    => 'whois.ua',
            'com'   => 'whois.verisign-grs.com',
            'gov'   => 'whois.dotgov.gov',
            'in'    => 'whois.inregistry.net',
            'info'  => 'whois.afilias.net',
            'org'   => 'whois.pir.org',
            'uk'    => 'whois.nic.uk',
            'us'    => 'whois.nic.us',
            'xn--p1ai' => 'whois.tcinet.ru',
            'рф'    => 'whois.tcinet.ru',
            'ru'    => 'whois.tcinet.ru',
            'net'   => 'whois.verisign-grs.com',
            'su'    => 'whois.tcinet.ru',
            'biz'   => 'whois.biz',
            'me'    => 'whois.nic.me',
            'md'    => 'whois.nic.md',
            'kz'    => 'whois.nic.kz',
            'cc'    => 'ccwhois.verisign-grs.com',
            'tv'    => 'tvwhois.verisign-grs.com',
            'tr'    => 'whois.nic.tr',
            'ro'    => 'whois.rotld.ro',
            'am'    => 'whois.amnic.net',
            'cn'    => 'whois.cnnic.cn',
            'ws'    => 'whois.website.ws',
            'au'    => 'whois.audns.net.au',
            'kg'    => 'whois.domain.kg'
        );
        
        if( !isset( $whois_service_ar[$zone] ) ){ echo $domain." !not zone \n"; return FALSE; }
        
        // Соединение с сокетом TCP, ожидающим на сервере "whois.arin.net" по 
        // 43 порту. В результате возвращается дескриптор соединения $sock.
        $sock = fsockopen($whois_service_ar[$zone], 43, $errno, $errstr);
        if (!$sock){ return FALSE; }
        else
        {
            // Записываем строку из переменной $_POST["ip"] в дескриптор сокета.
            fputs ($sock, $domain."\r\n");
            // Осуществляем чтение из дескриптора сокета.
            $text = "";
            while (!feof($sock))
            {
              $text .= fgets ($sock, 128);#."<br>";
            }
            // закрываем соединение
            fclose ($sock);

            // Ищем реферальный сервере
            $pattern = "|ReferralServer: whois://([^\n<:]+)|i";
            preg_match($pattern, $text, $out);
            if(!empty($out[1])) return $this->whois($out[1], $domain);
            else return $text;
        }
    }
    
    function cy_pr_com(){
        header("Content-type:text/plain;Charset=utf-8");
        
        $dns        = 'ns1.freedns.ws';
        $cnt_page   = 7;
        
        for($i=1; $i<=$cnt_page; $i++){
            
            $url = 'http://www.cy-pr.com/nameserver/'.$dns.'/'.$i;
            
            $html_list = $this->parse_lib->down_with_curl($url);

            $html_obj = str_get_html($html_list);
            $ii = 0;
            foreach( $html_obj->find('.tablesorter td a') as $tmp_a ){
                $domain = $tmp_a->innertext;
                $domain = str_ireplace('www.', '', $domain);
                $domain = $this->idna_convert_lib->encode( $domain );
                $cy     = $this->yandexGetCY('http://www.'.$domain.'/');
                
                $whois = $this->whois($domain);
                
                if( stripos($whois, $dns) !== false ){
                    $this->db->query("  REPLACE INTO `donor_domain` 
                                        SET  
                                            `name`  = '{$domain}',
                                            `cy`    = '{$cy}',
                                            `dns_service`   = '{$dns}'
                                     ");
                }
                else echo ' - NO NS RECORD ';
                
                echo $domain.' - '.$cy."\n";
                flush();
                sleep(1);
            }
            
            $html_obj->clear();
            unset($html_obj);
        }
    }
    
    function stat_reg_ru(){
        header("Content-type:text/plain;Charset=utf-8");
        
        $cnt_page = 8;
        
        $dns = 'ns1.freedns.ws';
//        $dns = 'ns1.byet.org';
        
//        $top_domain = 'rf'; 
//        $top_domain = 'su';
        $top_domain = 'ru';
        
        //=== получение массива url-адресов ===//
        for($i=1; $i<=$cnt_page; $i++){
            
            $url = 'http://statonline.ru/domains?rows_per_page=200&tld='.$top_domain.'&dns='.$dns.'&page='.$i;
            
            $html_list = $this->parse_lib->down_with_curl($url);
            $html_list = iconv('windows-1251', 'utf-8', $html_list);

            $html_obj = str_get_html($html_list);

            foreach( $html_obj->find('a[title=Посмотреть WHOIS этого домена]') as $tmp_a ){
                $domain_url = $this->parse_lib->uri2absolute( $tmp_a->href , $url); //получение ссылки на домен
                preg_match("#domain=(.+)$#i", $domain_url, $url_ar);    //получение домена из строки
                $domain = iconv('windows-1251', 'utf-8', urldecode( $url_ar[1] ) ); //преобразование домена в текст (для РФ)
                $domain = $this->idna_convert_lib->encode( $domain );
                $cy     = $this->yandexGetCY('http://www.'.$domain.'/');
                
                $query = $this->db->query("SELECT `name` FROM `donor_domain` WHERE `name` = '{$domain}' LIMIT 1 ");
                if( $query->num_rows() > 0 ) continue; // пропуск если домен уже есть в БД
                
                echo $domain." - ".$cy."\n";
                
                $this->db->query("  INSERT INTO `donor_domain` 
                                    SET  
                                        `name`  = '{$domain}',
                                        `cy`    = '{$cy}'
                                 ");
                flush();                        
            }
            
            echo '--------------------- Страница '.$i."-----------------------\n";
            
            $html_obj->clear();
            unset($html_obj);
        }
        
    }
    
    function nslist_net(){
        header("Content-type:text/plain;Charset=utf-8");
        
        $cnt_page   = 107;
        $start_page = 105;
        
        for($i=$start_page; $i<=$cnt_page; $i++){
            $page_nbr = $i; 
            if( $page_nbr == 1 ) $page_nbr = '';
            
            $url = 'http://nslist.net/ns1.freedns.ws/'.$page_nbr;
            
            $html_list = $this->parse_lib->down_with_curl($url);
            $html_list = iconv('ISO-8859-1', 'utf-8', $html_list);
            
            $pattern = "#<h2>([a-z\d\.-]+)</h2>#i";
            preg_match_all($pattern, $html_list, $domainAr);
            
//            print_r( $domainAr );
            
            foreach ($domainAr[1] as $domain){
                
                $query = $this->db->query("SELECT `name` FROM `donor_domain` WHERE `name` = '{$domain}' LIMIT 1 ");
                if( $query->num_rows() > 0 ){ 
                    echo $domain." - уже существует в БД \n";
                    continue; // пропуск если домен уже есть в БД
                }
                
                $whoisData = $this->whois($domain);
                $nsPattern = "#ns\d\.freedns\.ws#i";
                
                if( !preg_match($nsPattern, $whoisData) ){
                    echo $domain." - DNS не найден \n";
                    continue; // пропуск при отсутствии нужного DNS
                }
                
                $cy = $this->yandexGetCY('http://www.'.$domain.'/');
                $pr = (int)$this->GetPageRank($domain);
                if( $pr < 1 ) $pr = 0;
                $dateTime = date("Y-m-d H:i:s"); 
                
                $this->db->query("INSERT INTO `donor_domain` SET  `name`='{$domain}', `cy`='{$cy}', `pr`='{$pr}', `pr_check`='{$dateTime}' ");
                
                echo 'OK - '.$cy.' - '.$pr.' - '.$domain."\n";
                
                sleep(5);
                flush();
            }
            
            
            echo '--------------------- Страница '.$i."-----------------------\n";
//            flush();
//            sleep(15);
        }
    }
    
    function new_hash_ar(){
        $path = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/application/controllers/donor_domain/';
        $dd = opendir( $path.'/cy.buttons/' );
        $hash_ar = array();
        while( $fname = readdir($dd) ){
            if( $fname != '.' && $fname != '..'){
                $content            = file_get_contents( $path.'/cy.buttons/'.$fname );
                $hash               = md5( $content );
                $hash_ar[ $hash ]   = trim($fname,'.gif');
            }
        }
        
        file_put_contents( $path.'cy2.txt', serialize($hash_ar) );
    }
    
    function get_donor(){
        header("Content-type:text/plain;Charset=utf-8");
        
        $query = $this->db->query("SELECT * FROM `donor_domain` WHERE `hosting` != '' AND `account` != '' ORDER BY `account`, `hosting`, `cy` DESC  ");
//        $query = $this->db->query("SELECT * FROM `donor_domain` WHERE `hosting` LIKE '%net76%' OR `hosting` LIKE '%webuda%' OR `hosting` LIKE '%netii%'  ORDER BY `account`, `hosting`, `cy` DESC  ");
        
        $i=1;
        foreach( $query->result_array() as $row ){
            $row['name'] = strtolower( $row['name'] );
            echo $i.'. '.$row['hosting'].' <a href="http://cy.'.$row['name'].'">cy.'.$row['name'].'</a><br />'; $i++;
//            echo '<a href="http://cy.'.$row['name'].'/">cy.'.$row['name'].'</a>'."\n";            
//            echo 'http://cy.'.$row['name']."/\n";
//            echo 'cy.'.$row['name']."\n";
            
//            if( $i%17 == 0 )
//                echo "---------------------------------------------------\n";
//            $i++;
        }
    }
    
    function update_cy(){
        header("Content-type:text/plain;Charset=utf-8");
        
        $query = $this->db->query("SELECT * FROM `donor_domain` ORDER BY `cy` DESC  ");
        
        $i=1;
        foreach( $query->result_array() as $row ){
            $new_cy = $this->yandexGetCY('http://'.$row['name']);
            if( $new_cy == 0 && $row['cy'] != 0 )
                $new_cy = $this->yandexGetCY('http://'.$row['name']);
            if( $new_cy == 0 && $row['cy'] != 0 )
                $new_cy = $this->yandexGetCY('http://'.$row['name']);
            echo $i.'. '.$row['cy'].' -> '.$new_cy.' - '.$row['name']."\n";
            
            $this->db->query("UPDATE `donor_domain` SET `cy`='{$new_cy}' WHERE `name`='{$row['name']}' ");
            
            flush();
            usleep(200000);
            $i++; 
        }
    }
    
    function upd_ns(){
        header("Content-type:text/plain;Charset=utf-8");
        $query = $this->db->query("SELECT * FROM `donor_domain` ORDER BY `cy` DESC  ");
        
        foreach( $query->result_array() as  $row){
            if( stripos($row['name'], '.spb.ru') ) continue;
            
            $whois_str = $this->whois( $row['name'] );
//            echo $whois_str;
            
            if( stripos( $whois_str, 'ns1.freedns.ws') === false ){
                $this->db->query("UPDATE `donor_domain` SET `delete` = 'delete' WHERE `name` = '{$row['name']}' ");
                echo "ERROR -\t".$row['name']."\n";
                flush();
            }
            else
                echo "OK - \t".$row['name']."\n";
            
            sleep(2);
        }
    }
    
    function upd_pr(){
        header("Content-type:text/plain;Charset=utf-8");
        
        $sql = "SELECT `id`, `name`, `pr` FROM `donor_domain` ORDER BY `pr_check` ";
        $query = $this->db->query($sql);
        
        foreach( $query->result_array() as  $row){
            $pr = (int) $this->GetPageRank( $row['name'] );
            if( $pr < 1 ) $pr = 0;
            
            $dateTime = date("Y-m-d H:i:s"); 
            
//            if( $pr != $row['pr'] ){
                $upd_sql = "UPDATE `donor_domain` SET `pr`='{$pr}', `pr_check` = '{$dateTime}' WHERE `id`='{$row['id']}' LIMIT 1";
                $this->db->query($upd_sql);
//            }
            
            echo "PR: {$row['pr']} -> {$pr} \t {$row['name']}\n";
            flush();
            sleep(1);
        }
        
    }
    
    private function GetPageRank($q, $host = 'toolbarqueries.google.com', $context = NULL) {

        $seed = "Mining PageRank is AGAINST GOOGLE'S TERMS OF SERVICE. Yes, I'm talking to you, scammer.";
        $result = 0x01020345;
        $len = strlen($q);
        for ($i = 0; $i < $len; $i++) {
            $result^=ord($seed{$i % strlen($seed)}) ^ ord($q{$i});
            $result = (($result >> 23) & 0x1ff) | $result << 9;
        }
        if (PHP_INT_MAX != 2147483647) {
            $result = -(~($result & 0xFFFFFFFF) + 1);
        }
        $ch = sprintf('8%x', $result);
        $url = 'http://%s/tbr?client=navclient-auto&ch=%s&features=Rank&q=info:%s';
        $url = sprintf($url, $host, $ch, $q);
    //    echo $url.'<br />';
    //    @$pr = file_get_contents($url, false, $context);
        $pr = $this->check_pr_curl($url);
        return $pr ? substr(strrchr($pr, ':'), 1) : false;
    }
    
    private function check_pr_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        $content = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != 200)
            exit(' HTTP Code != 200 (Ban?)');

        return $content;
    }

}