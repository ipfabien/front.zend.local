<?php

/**
 * indexController
 * 
 * @author
 * @version 
 */

class Wsserver_IndexController extends Zend_Controller_Action 
{
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		ini_set('soap.wsdl_cache_enabled', 0);
		
		/**
		 * Limitation par place d'adresses IP
		 */
		$rangeBegins = ip2long('127.0.0.1');
		$rangeEnds   = ip2long('127.0.0.255');
		$clientIp    = ip2long($_SERVER['REMOTE_ADDR']);
		if( $rangeBegins < $clientIp || $clientIp >= $rangeEnds ){
			$this->getResponse()->setHttpResponseCode(401);
			$this->getResponse()->sendResponse();
			exit(0);
		}
		
		
		
		/**
		 * Authentification basique HTTP
		 * Fonctionne avec PHP/CGI en remplacement de Zend_Auth_Adapter_Http
		 * Nécessite deux modification : 
		 * 		cf. public/.htaccess ligne 45
		 * 		cf. public/index.php ligne 9
		 */
//		$service = new User_Service_User();
//		if( !isset($_SERVER['PHP_AUTH_USER']) ||
//			!isset($_SERVER['PHP_AUTH_PW']) ||
//			User_Service_User::USER_AUTH_SUCCESS !== $service->authenticate( array( 'login' => $_SERVER['PHP_AUTH_USER'],
//																					'password' => $_SERVER['PHP_AUTH_PW'])
//									)
//	      ){
//				header( 'WWW-Authenticate: Basic realm="Authentification SOAP"' );
//				header( 'HTTP/1.0 401 Unauthorized' );
//				exit(0);
//			}
	}
	
	public function preDispatch()
	{
		
		/**
		 * Authentification basique HTTP
		 * Fonctionne uniquement avec PHP en tant que mod_apache
		 * Pour PHP/CGI : cf. init()
		 */
//		$config = array(
//			'accept_schemes' => 'basic',
//			'realm' => 'webservice',
//		);
//		
//		$adapter = new Zend_Auth_Adapter_Http($config);
//		$basicResolver = new Zend_Auth_Adapter_Http_Resolver_File();
//		$basicResolver->setFile(ROOT_PATH . DS .'var/SOAP/passwd.txt');
//		
//		
//		$adapter->setBasicResolver($basicResolver);
//		$request = Zend_Controller_Front::getInstance()->getRequest();
//		$response = Zend_Controller_Front::getInstance()->getResponse();
//		
//		$adapter->setRequest($request);
//		$adapter->setResponse($response);
//
//		$auth = $adapter->authenticate();
//		print_r( $auth );exit;
// 
//		if (!$auth->isValid()){
//			$adapter->setResponse($response);
//			Zend_Controller_Front::getInstance()->setResponse($response);
//			Zend_Controller_Front::getInstance()->getResponse()->sendResponse();
//			exit(0);
//		}
	}
	
	/**
	 * SOAP server
	 */
	public function soapAction() 
	{
		
		if( isset($_GET['wsdl']) ){
			$wsdl = new Zend_Soap_AutoDiscover();
			$wsdl->setClass('User_Service_User');
			$wsdl->handle();
		} else {
			$options = array( 'soap_version' => SOAP_1_1, 
			'uri' => 'http://front.zend.local/Wsserver/index/soap');
			
			$server = new Zend_Soap_Server( null, $options);
			$server->setClass('User_Service_User');
			$server->handle();
		}
	}
	
	/**
	 * XMLRPC server
	 */
	public function xmlrpcAction() 
	{
		$cacheFile = ROOT_PATH . '/var/cache/xmlrpc.cache';
		$server = new Zend_XmlRpc_Server();
		
		if( !Zend_XmlRpc_Server_Cache::get( $cacheFile, $server )){
			$server->setClass( 'User_Service_User', 'user' );
			Zend_XmlRpc_Server_Cache::save( $cacheFile, $server);
		}
		echo $server->handle();
	}
	
	/**
	 * JSONRPC server
	 */
	public function jsonrpcAction() 
	{
		$server = new Zend_Json_Server();
		$server->setClass( 'User_Service_User', 'user' );
		echo $server->handle();
	}
	
	/**
	 * JSON
	 */
	public function jsonuserlistAction() 
	{
		$userService = new User_Service_User;
		$users = $userService->listUsers();
		print Zend_Json::encode($users);
		exit;
	}

}
