<?php

class Remote_serv_transfer_lib {
    
    private $fileList, 
            $remoteHost,
            $remoteAddUri;
    
    function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->config('multidomaine');
        $this->ci->load->library('Multidomaine_lib');
        
        $this->remoteHost   = STATIC_REMOTE_HOST;
        $this->remoteAddUri = STATIC_REMOTE_ADD_URI;
    }
    
    function __destruct() {
//        print_r($this->fileList);
    }
    
    function send_file_to_remote(){
        // Cleaner files array
        $this->fileList = array();
        // Scan image files
        $this->scan_upload();
        
        foreach($this->fileList as $filePath_Ar){
            $fileData['file']       = '@'.realpath($filePath_Ar['file_path']);
            $fileData['file_path']  = $filePath_Ar['remote_file_path'];
            
            $remoteURL = 'http://'.$this->remoteHost.$this->remoteAddUri;
            
            echo $this->curl_post($remoteURL, $fileData);
            
            unlink($filePath_Ar['file_path']);
        }
    }
    
    private function scan_upload($path = false){
        if($path == false){
            $path = './upload';
        }
        
        $dirList = scandir($path);
        
        if(is_array($dirList)){
            foreach($dirList as  $fname){
                if($fname == '.' || $fname == '..'){
                    continue;
                }
                
                $filePath = $path.'/'.$fname;
                
                if(is_dir($filePath)){
                    $this->scan_upload($path.'/'.$fname);
                }
                else{
                    //Change real host in path on static host
                    $remoteFilePath = $this->change_host_in_path($filePath);
                    $this->fileList[] = array('file_path'=>$filePath, 'remote_file_path'=>$remoteFilePath);
                }
            }
        }
    }
    
    private function curl_post($url, $fileDataAr){
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileDataAr);
        
	$content = curl_exec($ch);
	curl_close($ch);

	return $content;
    }
    
    private function change_host_in_path($path){
        
        // Get Host from path
        $pathHost = $this->get_host_from_path($path);
        
        // If not Host in path
        if($pathHost == false){ return $path; }
        
        //Data for host from config
        $hostData = $this->ci->multidomaine_lib->getHostData($pathHost); 
        
        // Replace real Host in path on static Host 
        $path = preg_replace("#{$pathHost}#i", $hostData['static_serer'], $path); 
        
        return $path;
    }
    
    private function get_host_from_path($path){
        //Pattern for host in path
        $pattern_HostInPath = "#/([a-z\.-]+\.[a-z]{2,7})/#i"; 
        
        preg_match($pattern_HostInPath, $path, $resultAr);
        
        // Check true resault
        if(!is_array($resultAr) || !isset($resultAr[1]) || empty($resultAr[1]) ){
            return false;
        }
        
        return $resultAr[1];
    }
}

