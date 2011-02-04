<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to root directory
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(dirname(__FILE__))) );

defined('CONFIG_PATH')
    || define('CONFIG_PATH',APPLICATION_PATH . '/configs' );
    

    // proxy HTTP/1.0 fixs
if ( getenv('APPLICATION_ENV') ) {
	define('APPLICATION_ENV', getenv('APPLICATION_ENV'));
} else if ( getenv('REDIRECT_APPLICATION_ENV') ){
	define('APPLICATION_ENV', getenv('REDIRECT_APPLICATION_ENV'));
} else {
    define('APPLICATION_ENV', 'production');
}

// Populates PHP_AUTH env variables with PHP running as CGI
// @see .htaccess
if(  isset( $_SERVER['REDIRECT_REMOTE_USER'] ) && $_SERVER['REDIRECT_REMOTE_USER']!= '' ){
	list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['REDIRECT_REMOTE_USER'], 6)));
} elseif ( isset( $_SERVER['REMOTE_USER'] ) && $_SERVER['REMOTE_USER']!= '' ){
	list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['REMOTE_USER'], 6)));
}

// Define absolute & relative URIs
defined('URL_MAIN_REL')
    || define ('URL_MAIN_REL', rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/');
defined('URL_MAIN_ABS')
    || define ('URL_MAIN_ABS', 'http://' . $_SERVER['HTTP_HOST'] . URL_MAIN_REL);
    

// Define some usefull constants
define ( 'DS' , DIRECTORY_SEPARATOR );
define ( 'PS' , PATH_SEPARATOR );
define ( 'TAB' , "\t" );

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(ROOT_PATH . DS . 'library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    CONFIG_PATH . DS . 'application.ini'
);

$application->bootstrap()
            ->run();
			
			
			
			
			