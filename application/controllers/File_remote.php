<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('BASEPATH')) exit('No direct script access allowed');

class File_remote extends CI_Controller {
    
    private $allowIpList = array('109.86.165.207','82.146.49.219', '185.159.131.88', '185.246.67.131');
            
    function __construct() {
        parent::__construct();
        
        // Check allow IP
        if( $this->allowIp() === false){ echo "This IP not in allow list"; exit(); }

        $this->load->library('upload');
        $this->load->library('Dir_lib');
    }
    
    
    function add_file(){
        
        $filePath   = $_POST['file_path'];
        
        if(is_file($filePath)){
            echo 'File Exist: '.$filePath."<br />\n";
            exit();
        }
        
        //Get directory to wright
        $dir    = $this->get_dirpath_from_filepath($filePath);
        $fname  = $this->get_fname_from_filepath($filePath);
        
        if( !is_dir($dir) ){ //create directory
            $this->dir_lib->createDir($dir);
        }
        
        $this->local_upload($fname, $dir);
    }
    
    function del_file(){
        $filePath   = $_POST['file_path'];
        
        if(!preg_match("#^\./upload.+#", $filePath)){
            echo "\nERROR: путь не коректен {$filePath} \n";
            exit();
        }
        
        $fName = $filePath;
        
        $msg = '';
        if(is_file($fName))
        {
            if(unlink($fName)){
                $msg .= 'OK: Файл удален - '.$fName."\n";
            }
            else{
                $msg .= 'ERROR: Ошибка удаления файла - '.$fName."\n";
            }
        }
        else{
            $msg .= 'NOTE: Файл не найден - '.$fName."\n";
        }
        
        echo $msg;
    }
    
    
    private function allowIp(){
        $remoteIP   = $_SERVER['HTTP_X_REAL_IP'];
        $ipAr       = $this->allowIpList;
        
        if(array_search($remoteIP, $ipAr) !== false){
            return true;
        }
        else{
            return false;
        }
    }
    
    private function get_dirpath_from_filepath($filePath){
        return  $dirPath = preg_replace("#/[^/]+$#i", '/', $filePath);
    }
    
    private function get_fname_from_filepath($filePath){
        preg_match("#/([^/]+)$#i", $filePath, $fnameAr);
        
        return $fnameAr[1];
    }
    
    private function local_upload( $fname, $fpath ){
        $config['upload_path']      = $fpath;
        $config['allowed_types']    = 'gif|jpg|jpeg|png';
        $config['max_size']         = 0;
        $config['max_width']        = 0;
        $config['max_height']       = 0;
        $config['encrypt_name']     = false;
        
        $this->upload->initialize($config);
        
        if( !$this->upload->do_upload( 'file' ) ){
            echo $this->upload->display_errors();
            return FALSE;
        }
        
//        $this->file_data = $this->upload->data();
        
        return TRUE;
    }
    
}