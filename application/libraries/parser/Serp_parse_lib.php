<?php


class Serp_parse_lib
{
    private $yandexUrl  = 'https://xmlsearch.yandex.com/xmlsearch?user=skybee84&key=03.47372985:0ec36ca944189961ac6da27d41f5d404&maxpassages=5&groupby=groups-on-page%3D20';
    private $thisHost   = false;

    function setThisHost($thisHost)
    {
        $this->thisHost = $thisHost;
    }

    function getData($articleData)
    {
        $title      = $this->cleanQuery($articleData['title']);
        $queryUrl   = $this->yandexUrl .'&query='.$title;
        $serpXmlStr = $this->down_with_curl($queryUrl);
        $serpXmlStr = $this->cleanXmlStr($serpXmlStr);

        $xmlObj     = simplexml_load_string($serpXmlStr);
        if($xmlObj === false){
            return false;
        }

        $data = $this->getDataFromXml($xmlObj);
        if($data){
            $data = $this->delThisHostFromResult($data, $this->thisHost);
        }

        return $data;
    }

    private function getDataFromXml($xml)
    {
        if( isset($xml->response->error) ) {
            $errorMsg = "Serp Parse Error: ".$xml->response->error;
            log_message('error', $errorMsg);

            return false;
        }

        $results = $xml->response->results->grouping->group;

        $data = array();
        foreach($results as $row){
            $tmpRow['title']    = (string) $row->doc->title;
            $tmpRow['host']     = (string) $row->doc->domain;
            $tmpRow['url']      = (string) $row->doc->url;

            $tmpRow['text'] = '';
            
            if(isset($row->doc->headline)){
                $tmpRow['text'] .= (string) $row->doc->headline;
            }
            if(isset($row->doc->passages)){
                $tmpRow['text'] .= (string) $row->doc->passages;
            }

            $data[] = $tmpRow;
        }

        return $data;
    }

    private function delThisHostFromResult($results, $thisHost = false)
    {
        if(!$thisHost){
            $thisHost = $_SERVER['HTTP_HOST'];
        }

        if(is_array($results) && count($results) < 1){
            return $results;
        }

        $newResults = array();
        foreach($results as $res){
            if($res['host'] == $thisHost){
                continue;
            }

            $newResults[] = $res;
        }

        return $newResults;
    }

    private function cleanXmlStr($str)
    {
        $pattern = "/<\/?(hlword|passage)>/";
        $str = preg_replace($pattern, ' ', $str);
        $str = str_replace('...', ' ', $str);
        return $str;
    }

    private function cleanQuery($query)
    {
        $query = html_entity_decode($query);
        $query = urlencode($query);
        return $query;
    }

    private function down_with_curl($url)
    {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko' );
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

	$content = curl_exec($ch);
	curl_close($ch);

	return $content;
    }
}

