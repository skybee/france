<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hostRoutes['ru'] = array('smiexpress.ru','express.odnako.su');
$hostRoutes['fr'] = array('france.lh');


// <404 Image redirect>
$imageUriPatter = "#/upload/images/#i";
if(preg_match($imageUriPatter, $_SERVER['REQUEST_URI'])){
    include_once './noimage.php';
    
    if(isset($_GET['content'])){
        $noImageUrl = getNoImageUrlContent(); // изображения внутри текста
    }
    else{
        $noImageUrl = getNoImageUrl();
    }
    
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: ".$noImageUrl); 
    exit($_SERVER['REQUEST_URI']);
}
// </404 Image redirect>


/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']                                    = "main";
$route['[-a-z\d/]+?/(-\d+-.+)']                                 = "main/document/$1"; #/news/ukraine/-id-****/
//$route['(actualite|divertissement|sport|finance|lifestyle)$']   = "main/main_page/$1";

//if ( in_array($_SERVER['HTTP_HOST'],$hostRoutes['ru']) )
//{
//    $route['^(news|finance|lifestyle)$']        = "main/main_page/$1";
//}
//if ( in_array($_SERVER['HTTP_HOST'],$hostRoutes['fr']) ) {
//    $route['^(actualite|lifestyle)$']           = "main/main_page/$1";
//}
$route['^(:any)$']        = "main/main_page/$1";

$route['search/(:any)']                                         = "main/search/$1";
$route['tmp/(:any)']                                            = "tmp/$1";
$route['[-a-z\d/]+?/([-a-z\d]+?)/?(\d*)']                       = "main/cat_list/$1/$2";
$route['parser/(:any)']                                         = "parser/$1";
$route['donor/(:any)']                                          = "donor/$1";
$route['ajax/(:any)']                                           = "ajax/$1";
//$route['tmp/(:any)']                                            = "tmp/$1";
$route['donor_admin/(:any)']                                    = "donor_admin/$1";
$route['donor_domain/(:any)']                                   = "donor_domain/$1";
$route['cron/(:any)']                                           = "cron/$1";
$route['(:any)']                                                = "main/$1";
$route['404_override'] = '';

$route['translate_uri_dashes'] = FALSE;
