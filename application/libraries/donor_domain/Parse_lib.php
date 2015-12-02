<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parse_lib{
    
    public  $img_dir; //принимает относительный путь от корня сайта
    private $real_img_dir; //полный путь для сохранения изображений


    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
//        $this->CI->load->helper('url_name_helper');
        $this->img_dir = '/upload/articles/';
        $this->real_img_dir = rtrim( $_SERVER['DOCUMENT_ROOT'],'/' ).$this->img_dir;
    }
    
    
    function down_with_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2' );
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	$content = curl_exec($ch);
	curl_close($ch);

	return $content;
    }
    
    function uri2absolute($link, $base){
        if (!preg_match('~^(http://[^/?#]+)?([^?#]*)?(\?[^#]*)?(#.*)?$~i', $link.'#', $matchesLink)) {
            return false;
        }
        if (!empty($matchesLink[1])) {
            return $link;
        }
        if (!preg_match('~^(http://)?([^/?#]+)(/[^?#]*)?(\?[^#]*)?(#.*)?$~i', $base.'#', $matchesBase)) {
            return false;
        }
        if (empty($matchesLink[2])) {
        if (empty($matchesLink[3])) {
            return 'http://'.$matchesBase[2].$matchesBase[3].$matchesBase[4];;
        }
        return 'http://'.$matchesBase[2].$matchesBase[3].$matchesLink[3];
        }
        $pathLink = explode('/', $matchesLink[2]);
        if ($pathLink[0] == '') {
            return 'http://'.$matchesBase[2].$matchesLink[2].$matchesLink[3];
        }
        $pathBase = explode('/', preg_replace('~^/~', '', $matchesBase[3]));
        if (sizeOf($pathBase) > 0) {
            array_pop($pathBase);
        }
        foreach ($pathLink as $p) {
            if ($p == '.') {
        continue;
        } elseif ($p == '..') {
        if (sizeOf($pathBase) > 0) {
        array_pop($pathBase);
        }
        } else {
        array_push($pathBase, $p);
        }
        }
        return 'http://'.$matchesBase[2].'/'.implode('/', $pathBase).$matchesLink[3];
    }  
    
    function clear_txt( $html ){
        $html = preg_replace("#<script[\s\S]*?</script>#i", '', $html);
        $html = preg_replace("#<iframe[\s\S]*?</iframe>#i", '', $html);
        $html = strip_tags($html, '<p> <img> <table> <tr> <td> <h1> <h2> <h3> <em> <i> <b> <strong> <ul> <ol> <li> <br> <center>');
        $html = $this->close_tags($html);
        
        return $html;
    }
    
    function close_tags($content){
        $position = 0;
        $open_tags = array();
        //теги для игнорирования
        $ignored_tags = array('br', 'hr', 'img');

        while (($position = strpos($content, '<', $position)) !== FALSE)
        {
            //забираем все теги из контента
            if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match))
            {
                $tag = strtolower($match[2]);
                //игнорируем все одиночные теги
                if (in_array($tag, $ignored_tags) == FALSE)
                {
                    //тег открыт
                    if (isset($match[1]) AND $match[1] == '')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]++;
                        else
                            $open_tags[$tag] = 1;
                    }
                    //тег закрыт
                    if (isset($match[1]) AND $match[1] == '/')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]--;
                    }
                }
                $position += strlen($match[0]);
            }
            else
                $position++;
        }
        //закрываем все теги
        foreach ($open_tags as $tag => $count_not_closed)
        {
            if( $count_not_closed < 0 ) $count_not_closed = 0;
            $content .= str_repeat("</{$tag}>", $count_not_closed);
        }

        return $content;
    }
    
    function get_donor_url( $page_url ){
        $url_ar = parse_url($page_url);
        return $url_ar['host'];
    }
    
    function get_fname_from_url( $url ){
        $url = trim($url);
        $url = preg_replace("#\.(jpg|jpeg|gif|png|bmp|tiff|js|css)\?\S+#i", ".$1", $url);
        $pattern = "#/([^/]+\.[a-z]{2,5})$#i";
        preg_match($pattern, $url, $fname_ar );
        
        if( isset($fname_ar[1]) )
            return $fname_ar[1];
        else 
            return FALSE;
    }
    
    function load_img( $img_url, $base_url){
        if( empty($img_url) ) return FALSE;
        
        $absolute_url   = $this->uri2absolute($img_url, $base_url);
        $new_img_name   = rand(100,999999).'_'.$this->get_fname_from_url($absolute_url);
            
        $img_buffer     = $this->down_with_curl($absolute_url); //скачивание изображения
        file_put_contents( $this->real_img_dir.$new_img_name, $img_buffer ); //сохранение изображения
        
        return $new_img_name;
    }
    
    function change_img_in_txt( $text, $base_url ){
        $pattern = "#src=['\"]([\s\S]*?)['\"]#i";
        preg_match_all($pattern, $text, $img_url_ar);
        $img_url_ar = $img_url_ar[1];
        
        if( count($img_url_ar) < 1 )  return $text; //прекращение обработки текста и возврат оригинала, в случае если карттинки не найдены
        
        foreach( $img_url_ar as $img_url ){
            $absolute_url   = $this->uri2absolute($img_url, $base_url);
            $img_name       = $this->get_fname_from_url($absolute_url);
            
            if( empty($img_name) )  continue;
            
            $new_img_name   = rand(100,999999).'_'.$img_name;
            $img_buffer     = $this->down_with_curl($absolute_url); //скачивание изображения
            file_put_contents( $this->real_img_dir.$new_img_name, $img_buffer ); //сохранение изображения

            $replace_img_ar['search'][]     = $img_url;
            $replace_img_ar['replace'][]    = $this->img_dir.$new_img_name;
        }
        
        if( isset($replace_img_ar) )
        $text = str_ireplace($replace_img_ar['search'], $replace_img_ar['replace'], $text);
        
        return $text;
    }
        
    function get_shingles_hash( $text, $shingle_length = 7 ){ //возвращает массив хэшей шинглов
        $text = iconv('utf-8', 'windows-1251', $text);
        $text = strip_tags($text);
        $html_pattern = "#&[a-z]{2,6};#i"; //== удаление мнимоники
        $text = preg_replace($html_pattern, ' ', $text);
        $text = strtolower($text);
        $pattern = "#\w{4,}#i";
        
        preg_match_all($pattern, $text, $word_ar);
        
        $word_ar = $word_ar[0];
           
        $count_word     = count($word_ar);
        $shingle_count  = $count_word - $shingle_length +1;
        
        $shingle_hash_ar = array();
        $shingle_str = '';
        for($i=0; $i<=$shingle_count; $i++ ){
            $stop_word_id = $i+$shingle_length; //id последнего слова для данного шингла
            for($ii=$i; $ii<$stop_word_id && $ii<$count_word; $ii++){
                $shingle_str .= $word_ar[$ii].' ';
            }
            $shingle_hash_ar[] = crc32($shingle_str);
            $shingle_str = '';
        }
        
        return $shingle_hash_ar;
    }
    
    function comparison_shingles_hash( $hash_ar_1, $hash_ar_2, $percent=60){ //принимает два массива хешей для сравнения и процент определяющий при каком колличестве совпадений тексты считаются идентичными
        
        if( !is_array($hash_ar_1) || !is_array($hash_ar_2) ) return FALSE;
        
        $cnt_hash = count($hash_ar_1);
        $cnt_comparison     = 0; //количество сравнений
        $cnt_coincidence    = 0; //количество совпадений
        
        for($i=0; $i<$cnt_hash; $i++){
            if($i%5 == 0){ //сравнение каждого пятого хеша
                if( in_array($hash_ar_1[$i], $hash_ar_2) ){
                        $cnt_coincidence++;
                }        
                $cnt_comparison++;
            } 
        }
        
        $percent_coincidence = round( $cnt_coincidence / ($cnt_comparison/100) ); //процент совпадений
        
        echo $percent_coincidence.'% совпадений '.$cnt_comparison.'/'.$cnt_coincidence."\n";
        
        if($percent_coincidence >= $percent) //документы идентичны
            return TRUE;
        else    //документы различны
            return FALSE;
    }
    
    function get_like_news_hash( $new_article_hash_ar ){
        
        if( count($new_article_hash_ar) < 1 ){ 
            echo "Масив хешей пуст\n";
            return FALSE;
        }
        
        $query_hash['first']    = $new_article_hash_ar[10];
        $query_hash['middle_1'] = $new_article_hash_ar[ round(count($new_article_hash_ar)/2) ];
        $query_hash['middle_2'] = $new_article_hash_ar[ round(count($new_article_hash_ar)/3) ];
        $query_hash['middle_3'] = $new_article_hash_ar[ round(count($new_article_hash_ar)/4) ];
        $query_hash['last']     = $new_article_hash_ar[ count($new_article_hash_ar)-11 ];
        
        $query = $this->CI->db->query(" SELECT `id`, `shingles_hash` AS `hash_ar` FROM `articles`  
                                        WHERE 
                                            `shingles_hash` LIKE '%{$query_hash['first']}%'
                                            OR
                                            `shingles_hash` LIKE '%{$query_hash['middle_1']}%'
                                            OR
                                            `shingles_hash` LIKE '%{$query_hash['middle_2']}%'
                                            OR
                                            `shingles_hash` LIKE '%{$query_hash['middle_3']}%'
                                            OR
                                            `shingles_hash` LIKE '%{$query_hash['last']}%'
                                        LIMIT 100");                                          
                                            
        if( $query->num_rows() < 1 ) return FALSE;
        
        foreach($query->result_array() as $row){
            if( !$hash_ar[ $row['id'] ] = unserialize( $row['hash_ar'] ) ){ 
                $hash_ar[ $row['id'] ] = NULL;
            }    
        }
        return  $hash_ar;
    }
    
    function insert_news( $data_ar, $count_word = 80, $rewrite_news = true ){ //принимает массив array('url','img','title','text','date') и минимальный размер текста(колличество слов более 4 букв) ; 
        $data_ar['text']    = $this->clear_txt( $data_ar['text'] );
        $data_ar['title']   = $this->db->escape_str( strip_tags($data_ar['title']) );
        $data_ar['donor']   = $this->get_donor_url( $data_ar['url'] );
        $this_hash_ar       = $this->get_shingles_hash( $data_ar['text'] );
        
        if( count($this_hash_ar) < $count_word ){ echo "error #1 small text \n"; return FALSE;}
        
        $like_hash_list     = $this->get_like_news_hash( $this_hash_ar );
        
        if( $like_hash_list != false ){ //сравнение хешей
            foreach( $like_hash_list as $news_id => $like_hash_ar ){
                if( $this->comparison_shingles_hash($this_hash_ar, $like_hash_ar, 60) == true ){ //если найденно совпадение текста
                    
                    if( count($this_hash_ar) > count($like_hash_ar) && $rewrite_news == true ){ //если новый текст больше старого, то перезапись старого текста новым
                        
                        $data_ar['text'] = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
                        $this->CI->db->query("  UPDATE `articles` 
                                                SET 
                                                    `title`         = '{$data_ar['title']}', 
                                                    `text`          = '".$this->db->escape_str($data_ar['text'])."',
                                                    `donor_url`     = '{$data_ar['url']}',
                                                    `donor_host`    = '{$data_ar['donor']}',    
                                                    `shingles_hash` = '".serialize($this_hash_ar)."' 
                                                WHERE `id`='{$news_id}' 
                                             ");
                        echo 'ОК - Запись перезаписана ID-'.$news_id.' - '.$data_ar['title']."\n";                        
                    }
                    echo "error #2 clone text. CloneID-".$news_id.' '.$data_ar['title']."\n";
                    return FALSE;
                }
            }
         }   
         $data_ar['text']        = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
         $data_ar['img_name']    = $this->load_img( $data_ar['img'], $data_ar['url']  );
//         $data_ar['url_name']    = seoUrl( $data_ar['title'] );
         $data_ar['url_name']    = url_slug( $data_ar['title'] ,array('transliterate' => true));
            
         $this->CI->db->query("  INSERT INTO `articles` 
                                 SET
                                    `title`         = '{$data_ar['title']}', 
                                    `text`          = '".$this->db->escape_str($data_ar['text'])."',
                                    `img`           = '{$data_ar['img_name']}',
                                    `date`          = '{$data_ar['date']}',
                                    `url_name`      = '{$data_ar['url_name']}',
                                    `donor_url`     = '{$data_ar['url']}',
                                    `donor_host`    = '{$data_ar['donor']}',    
                                    `shingles_hash` = '".serialize($this_hash_ar)."'  
                               ");
        echo 'ОК - Занесена новая новость ID# '.$this->CI->db->insert_id().' - '.$data_ar['title']."\n";
        return TRUE;
    }
}