<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parse_lib{
    
    public  $img_dir; //принимает относительный путь от корня сайта
    private $real_img_dir; //полный путь для сохранения изображений


    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
//        $this->CI->load->helper('parser/url_name_helper');
        $this->CI->load->helper('parser/url_name2_helper');
        $this->CI->load->library('dir_lib');
        $this->CI->load->library('image_lib');
        $this->img_dir = '/upload/news/';
        $this->real_img_dir = rtrim( $_SERVER['DOCUMENT_ROOT'],'/' ).$this->img_dir;
    }
    
    
    static function down_with_curl($url, $getInfo = false, $useProxy = false, $useCount=0){
        
        if($useProxy !== false){
            $proxy = self::getRandProxy();
        }
        
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko' );
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if(isset($proxy) && $proxy!=false){
            $proxyIpPortAr = explode(':', $proxy);
            curl_setopt($ch, CURLOPT_PROXY, $proxyIpPortAr[0]);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxyIpPortAr[1]);
//            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            echo "\n\n<br />USE PROXY: {$proxy}<br />\n\n";
        }
        
	$content    = curl_exec($ch);
        $httpData   = curl_getinfo($ch);
        
//        echo "\n<br/>----------------------<br/>\n";
//        print_r($httpData);
//        echo curl_error($ch);
//        echo "\n<br/>----------------------<br/>\n";
        
	curl_close($ch);
        
        if($getInfo==false)
        {
            if(empty($content) && $useCount<5){
                $content = Parse_lib::down_with_curl($url, $getInfo, $useProxy, $useCount+1);
            }
            return $content;
        }
        else
        {
            if(empty($content) && $useCount<5){
                $returnAr = Parse_lib::down_with_curl($url, $getInfo, $useProxy, $useCount+1);
            }
            $returnAr['data']       = $content;
            $returnAr['http_data']  = $httpData;
            
            return $returnAr;
        }
    }
    
    static function uri2absolute($link, $base){
        
        $link = preg_replace("#^//#i", "http://", $link);
//        $link = str_ireplace('https://', 'http://', $link);
        
        if (!preg_match('~^(http[s]?://[^/?#]+)?([^?#]*)?(\?[^#]*)?(#.*)?$~i', $link.'#', $matchesLink)) {
            return false;
        }
        if (!empty($matchesLink[1])) {
            return $link;
        }
        if (!preg_match('~^(http[s]?://)?([^/?#]+)(/[^?#]*)?(\?[^#]*)?(#.*)?$~i', $base.'#', $matchesBase)) {
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
        $html = parse_lib::uncomment_tags($html); //возврат закомментированного содержимого
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
        $host   = str_ireplace('www.', '', $url_ar['host']);
        return $host;
    }
    
    function get_fname_from_url( $url ){
        $url = trim($url);
        $url = preg_replace("#\.(jpg|jpeg|gif|png|bmp|tiff|js|css)\?\S+#i", ".$1", $url);
        $pattern = "#/([^/]+\.[a-z]{2,5})$#i";
        preg_match($pattern, $url, $fname_ar );
        
        if( isset($fname_ar[1]) ){
            $fname_ar[1] = preg_replace("#[^a-zA-Z\d\.-]#i", '', $fname_ar[1]);
            
            return $fname_ar[1];
        }
        else 
            return FALSE;
    }
    
    function getImgExtensionFromMType($mimeType){
        $exAr = array(
            'image/png'     =>'png',
            'image/jpeg'    =>'jpg', 
            'image/gif'     =>'gif', 
            'image/bmp'     =>'bmp', 
            'image/vnd.microsoft.icon' =>'ico', 
            'image/tiff'    =>'tiff', 
            'image/svg+xml' => 'svg'
        );
        
        if(isset($exAr[$mimeType]))
        {
            $extension = $exAr[$mimeType];
        }
        else
        {
            $extension = 'img';
        }
        
        return '.'.$extension;
    }
    
    function getLoadImgFname($mimeType, $alt = ''){
        if(!empty($alt))
        {
            $newAlt     = url_slug( $alt ,array('transliterate' => true));
            $newAlt     = $newAlt.'_';
            $newAlt     = mb_substr($newAlt,0, 100); 
            $newAlt     = preg_replace("#_[^_]+#i", '', $newAlt); //удаление обрезанного слова
            $imgName    = $newAlt.'_'.mt_rand(100,999999).'_'.$this->getImgExtensionFromMType($mimeType);
        }
        else
        {
            $imgName    = md5(mt_rand(100,999999).'_'.time()).'_'.$this->getImgExtensionFromMType($mimeType);
        }
        
        return $imgName;
    }
    
    function load_img( $img_url, $base_url, $imgAlt = '', $flip = true){
        if( empty($img_url) ) return FALSE;
        
        $absolute_url   = $this->uri2absolute($img_url, $base_url);
        $imgDataAr      = $this->down_with_curl($absolute_url, true, true); //скачивание изображения
        
//        $new_img_name   = $this->get_fname_from_url($img_url);
        $new_img_name   = $this->getLoadImgFname($imgDataAr['http_data']['content_type'], $imgAlt);
        
        $this->lastLoadImgName  = $new_img_name;
        $savePathName           = $this->CI->dir_lib->getImgRdir().$new_img_name;
        $imgNameWithDatePath    = $this->CI->dir_lib->getDatePath().$new_img_name;
        
        file_put_contents( $savePathName, $imgDataAr['data'] ); //сохранение изображения
        
        if($flip)
        {
            $this->flipImg($savePathName);
        }
        
        return $imgNameWithDatePath;
    }
    
    function resizeImg( $resize = 'medium', $sizeAr = false ){
        
        $pathToImg = $this->CI->dir_lib->getImgRdir().$this->lastLoadImgName;
        
        if( !file_exists($pathToImg) ) 
            return false;
        
        switch( $resize ){
            case 'medium' :
                $savePath = $this->CI->dir_lib->getImgMdir().$this->lastLoadImgName;
                $width  = '300';
                $height = '400';
                break;
            case 'small' :
                $savePath = $this->CI->dir_lib->getImgSdir().$this->lastLoadImgName;
                $width  = '120';
                $height = '100';
                break;
            default :
                return false;
        }
        
        $config['source_image']     = $pathToImg;
        $config['new_image']        = $savePath;
        $config['width']            = $width;
        $config['height']           = $height;
        
        if( $sizeAr != false && $sizeAr['width'] > 10 && $sizeAr['height'] > 10 ){
            $config['width']        = $sizeAr['width'];
            $config['height']       = $sizeAr['height'];
        }
        
        $this->CI->image_lib->initialize($config);
        $this->CI->image_lib->resize();
    }  
    
    function flipImg($imgPathName){
        
        $filename = $imgPathName;#$this->CI->dir_lib->getImgRdir().$this->lastLoadImgName;
        
        preg_match("#\.([a-z]{3,4})$#", $filename, $arr);

        if(isset($arr[1]) && !empty($arr[1]))
        {
            switch ($arr[1])
            {
                case 'png':
                    $header = 'image/png';
                    $createImgFunc = '$im = imagecreatefrompng($filename);';
                    $outputImgFunc = 'imagepng($im,$filename);';
                    break;
                case 'jpg':
                    $header = 'image/jpeg';
                    $createImgFunc = '$im = imagecreatefromjpeg($filename);';
                    $outputImgFunc = 'imagejpeg($im,$filename,100);';
                    break;
                case 'jpeg':
                    $header = 'image/jpeg';
                    $createImgFunc = '$im = imagecreatefromjpeg($filename);';
                    $outputImgFunc = 'imagejpeg($im,$filename,100);';
                    break;
                default :
                    $header = false;
            }
        }
        
        if(!$header)
        {
            return false;
        }

        // Load
        eval($createImgFunc);

        // Flip it horizontally
        imageflip($im, IMG_FLIP_HORIZONTAL);

        // Output
        eval($outputImgFunc);
        imagedestroy($im);
    }
    
    function change_img_in_txt( $text, $base_url ){
        
        $html_obj   = str_get_html($text);
        $imgList    = $html_obj->find('img'); 
        
        if( count($imgList) < 1 ) return $text; //прекращение обработки текста и возврат оригинала, в случае если карттинки не найдены
        
        foreach($imgList as $imgObj){
            
            $imgAlt = '';
            if(isset($imgObj->attr['alt']) && !empty($imgObj->attr['alt']))
            {
                $imgAlt = $imgObj->attr['alt'];
            }
            
            $imgPathName   = $this->load_img($imgObj->src, $base_url, $imgAlt);            
            $imgPathName   = '/'.$this->CI->dir_lib->getImgRdir(false,false).$imgPathName; //!-- get from dir_lib
            

            if( isset($imgObj->slider) && $imgObj->slider == 'slider' ){
                $this->resizeImg('small', array('width'=>110, 'height'=>300) );
                $smallImgUri       = $this->CI->dir_lib->getImgSdir().$this->lastLoadImgName;
                $imgObj->src       = '/'.$smallImgUri;
                $imgObj->realimg   = $imgPathName;
            }
            else{
                $imgObj->src       = $imgPathName;
            }
        }
        
        return $html_obj->save();
    }
    
    function get_shingles_hash( $text, $shingle_length = 7 ){ //возвращает массив хэшей шинглов
        $text = mb_strtolower($text);
//        $text = iconv('utf-8', 'cp1251//IGNORE', $text);
        $text = strip_tags($text);
        $html_pattern = "#&[a-z]{2,6};#i"; //== удаление мнимоники
        $text = preg_replace($html_pattern, ' ', $text);
        
        $pattern = "#(\pL{4,100})\W#ui";
        
        preg_match_all($pattern, $text, $word_ar);
        
        $word_ar = $word_ar[1];
           
        $count_word     = count($word_ar);
        $shingle_count  = $count_word - $shingle_length +1;
        
        $shingle_hash_ar = array();
        $shingle_str = '';
        for($i=0; $i<=$shingle_count; $i++ ){
            $stop_word_id = $i+$shingle_length; //id последнего слова для данного шингла
            for($ii=$i; $ii<$stop_word_id && $ii<$count_word; $ii++){
                $shingle_str .= $word_ar[$ii].' ';
            }
            if( $i%5 == 0)
//                $shingle_hash_ar[] = crc32($shingle_str);
                $shingle_hash_ar[] = sha1($shingle_str);
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
//            if($i%5 == 0){ //сравнение каждого пятого хеша
                if( in_array($hash_ar_1[$i], $hash_ar_2) ){
                        $cnt_coincidence++;
                }        
                $cnt_comparison++;
//            } 
        }
        
        $percent_coincidence = round( $cnt_coincidence / ($cnt_comparison/100) ); //процент совпадений
        
        echo $percent_coincidence.'% совпадений '.$cnt_comparison.'/'.$cnt_coincidence."<br />\n";
        
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
    
    function insert_news( $data_ar, $count_word = 80 ){ //принимает массив array('url','img','title','text','date') и минимальный размер текста(колличество слов более 4 букв) ; 
//        $data_ar['text']    = $this->clear_txt( $data_ar['text'] );
//        $data_ar['title']   = $this->db->escape_str( strip_tags( trim($data_ar['title']) ) );
//        $data_ar['donor']   = $this->get_donor_url( $data_ar['url'] );
//        $this_hash_ar       = $this->get_shingles_hash( $data_ar['text'] );
//        
//        if( count($this_hash_ar) < $count_word ){ echo "error #1 small text \n"; return FALSE;}
//        
//        $like_hash_list     = $this->get_like_news_hash( $this_hash_ar );
//        
//        if( $like_hash_list != false ){ //сравнение хешей
//            foreach( $like_hash_list as $news_id => $like_hash_ar ){
//                if( $this->comparison_shingles_hash($this_hash_ar, $like_hash_ar, 60) == true ){ //если найденно совпадение текста
//                    
//                    if( count($this_hash_ar) > count($like_hash_ar) ){ //если новый текст больше старого, то перезапись старого текста новым
//                        
//                        $data_ar['text'] = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
//                        $this->CI->db->query("  UPDATE `articles` 
//                                                SET 
//                                                    `title`         = '{$data_ar['title']}', 
//                                                    `text`          = '".$this->db->escape_str($data_ar['text'])."',
//                                                    `donor_url`     = '{$data_ar['url']}',
//                                                    `donor_host`    = '{$data_ar['donor']}',    
//                                                    `shingles_hash` = '".serialize($this_hash_ar)."' 
//                                                WHERE `id`='{$news_id}' 
//                                             ");
//                        echo 'ОК - Запись перезаписана ID-'.$news_id.' - '.$data_ar['title']."\n";                        
//                    }
//                    echo "error #2 clone text. CloneID-".$news_id.' '.$data_ar['title']."\n";
//                    return FALSE;
//                }
//            }
//         }   
//         $data_ar['text']        = $this->change_img_in_txt($data_ar['text'], $data_ar['url']); //замена изображений в тексте
//         $data_ar['img_name']    = $this->load_img( $data_ar['img'], $data_ar['url']  );
//         $data_ar['url_name']    = seoUrl( $data_ar['title'] );
//            
//         $this->CI->db->query("  INSERT INTO `articles` 
//                                 SET
//                                    `title`         = '{$data_ar['title']}', 
//                                    `text`          = '".$this->db->escape_str($data_ar['text'])."',
//                                    `img`           = '{$data_ar['img_name']}',
//                                    `date`          = '{$data_ar['date']}',
//                                    `url_name`      = '{$data_ar['url_name']}',
//                                    `donor_url`     = '{$data_ar['url']}',
//                                    `donor_host`    = '{$data_ar['donor']}',    
//                                    `shingles_hash` = '".serialize($this_hash_ar)."'  
//                               ");
//        echo 'ОК - Занесена новая новость ID# '.$this->CI->db->insert_id().' - '.$data_ar['title']."\n";
//        return TRUE;
    }
        
    static function comment_tags($str){
        $str = htmlspecialchars($str, ENT_NOQUOTES, 'UTF-8');
        return $str;
    }
    
    static function uncomment_tags($str){
        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        return $str;
    }
    
    static function validHTML( $html ){
        libxml_use_internal_errors( true );
        
        $dom = new DOMDocument;
//        $dom->encoding  = 'UTF-8';
        $dom->recover   = true;
        $dom->loadHTML($html);
        $dom->normalizeDocument();
        
        $html = $dom->saveHTML();


        
        
        return $html;
    }
    
    static function getRandProxy(){
        $proxyListFileName = './proxylist.txt';
        $randProxy = '';
        if(is_file($proxyListFileName)){
            $proxyListAr = file($proxyListFileName);
            
            if(is_array($proxyListAr)){
                shuffle($proxyListAr);
                
                $randProxy = $proxyListAr[0];
            }
        }
        
        if(!empty($randProxy)){
            return $randProxy;
        }
        else{
            return false;
        }
    }
}