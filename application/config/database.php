<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$host_lang = array(
    'express.lh'=>              'ru', //defult code = lh
    
    'smiexpress.ru'=>           'ru',
    'ru.pressfrom.com'=>        'ru',
    'ru.lalalay.com'=>          'ru',
    'ru.francais-express.com'=> 'ru',
    
    'fr.pressfrom.com'=>        'fr',
    'fr.lalalay.com'=>          'fr',
    'francais-express.com'=>    'fr',
    
    'de.pressfrom.com'=>        'de',
    'de.lalalay.com'=>          'de',
    'de.francais-express.com'=> 'de',
    
    'uk.pressfrom.com'=>        'uk',
    'uk.lalalay.com'=>          'uk',
    'uk.francais-express.com'=> 'uk',
    
    'us.pressfrom.com'=>        'us',
    'us.lalalay.com'=>          'us',
    'us.francais-express.com'=> 'us',
    
    'ca.pressfrom.com'=>        'ca',
    'ca.lalalay.com'=>          'ca',
    'ca.francais-express.com'=> 'ca',
    
    'au.pressfrom.com'=>        'au',
    'au.lalalay.com'=>          'au',
    'au.francais-express.com'=> 'au',
    
);

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => '',
	'password' => '',
	'database' => '',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => true,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db_ip = '5.9.104.44';


//if($_SERVER['HTTP_HOST'] == 'express.lh')
//{
//    $db['default']['pconnect'] = false;
//    
//    $db['default']['hostname'] = 'localhost';
//    $db['default']['username'] = 'root';
//    $db['default']['password'] = '';
//    $db['default']['database'] = 'fr-express'; //DB: france, fr-express
//
////    $db['default']['hostname'] = $db_ip;
////    $db['default']['username'] = 'admin_smi_en';
////    $db['default']['password'] = 'smi-en-ujyrjyu8444';
////    $db['default']['database'] = 'admin_smi_en';
//}



if($host_lang[$_SERVER['HTTP_HOST']] == 'lh'){
    $db['default']['hostname'] = 'localhost';
    $db['default']['username'] = 'root';
    $db['default']['password'] = '';
    $db['default']['database'] = 'fr-express'; //DB: france, fr-express
}


if($host_lang[$_SERVER['HTTP_HOST']] == 'ru'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_smi_ru';
    $db['default']['password'] = 'smi-ru-ujyrjyu8444';
    $db['default']['database'] = 'admin_smi_ru';
}


if($host_lang[$_SERVER['HTTP_HOST']] == 'fr'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_smi_fr';
    $db['default']['password'] = 'smi-fr-ujyrjyu8444';
    $db['default']['database'] = 'admin_smi_fr';
}


if($host_lang[$_SERVER['HTTP_HOST']] == 'de'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_smi_de';
    $db['default']['password'] = 'smi-de-ujyrjyu8444';
    $db['default']['database'] = 'admin_smi_de';
}

if($host_lang[$_SERVER['HTTP_HOST']] == 'uk'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_smi_en';
    $db['default']['password'] = 'smi-en-ujyrjyu8444';
    $db['default']['database'] = 'admin_smi_en';
}

if($host_lang[$_SERVER['HTTP_HOST']] == 'us'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_pf_us';
    $db['default']['password'] = 'pf-us-ujyrjyu8444';
    $db['default']['database'] = 'admin_pf_us';
}

if($host_lang[$_SERVER['HTTP_HOST']] == 'ca'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_pf_ca';
    $db['default']['password'] = 'pf-ca-ujyrjyu8444';
    $db['default']['database'] = 'admin_pf_ca';
}

if($host_lang[$_SERVER['HTTP_HOST']] == 'au'){
    $db['default']['hostname'] = $db_ip;
    $db['default']['username'] = 'admin_pf_au';
    $db['default']['password'] = 'pf-au-ujyrjyu8444';
    $db['default']['database'] = 'admin_pf_au';
}
