<?php

/**
 * indexController
 * 
 * @author
 * @version 
 */

class Wsclient_IndexController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function soapgetuserlistAction() 
	{
		$client = new Zend_Soap_Client('http://front.zend.local/Wsserver/index/soap?wsdl');
		$users = $client->listUsers();
		print_r( $users ); exit;
	}
	
	public function soapcreateuserAction() 
	{
		$client = new Zend_Soap_Client('http://front.zend.local/Wsserver/index/soap?wsdl');
		$client->setHttpLogin('test2')
			   ->setHttpPassword('test');
		$userData = array( 'login' => testSoap,
						   'password' => sha1( 'soap' ),
						   'nom' => 'WS',
						   'prenom' => 'SOAP11',
						   'telephone' => '00000000',
						   'email' => 'jhkljhl',
		                   'civilite' => 'M');

		$return = $client->create( $userData  );
		var_dump( $return ); exit;
	}
	
	/**
	 * XMLRPC user list
	 */
	public function xmlrpcgetuserlistAction() 
	{
		$client = new Zend_XmlRpc_Client('http://front.zend.local/Wsserver/index/xmlrpc');
		
		$systemService = $client->getProxy('system');
		
		$userService = $client->getProxy('user');
		
		$string = $userService->testString();
		var_dump( $string ); 
		
		$object = $userService->testObject();
		var_dump( $object ); 
		
		$array = $userService->testArray();
		var_dump( $array ); 
		
		$array = $userService->testArray2();
		var_dump( $array );
		
		$arrayOfOjects = $userService->listUsers();
		var_dump( $arrayOfOjects ); 
		
		$methods = $systemService->listMethods();
		print_r( $methods );
		
		$help = $systemService->methodSignature( 'user.authenticate' );
		print_r( $help );
		exit;
	}
	
	public function jsonAction()
	{
	
	}
}
