<?php

/** 
 * @desc Bla bla de présentation
 * @package User
 * @subpackage Service
 * @version 1.0 2011-02-02
 * @copyright Moi même
 * @author Administrateur
 * @license Privée
 *
 */
class User_Service_User 
{
	
	const USER_DATA_INVALID 			= 'userDataInvalid';
	const USER_NOT_SAVED 				= 'userNotSaved';
	const USER_AUTH_SUCCESS 			= 'userAuthSuccess';
	const USER_AUTH_IDENTITY_NOT_FOUND 	= 'userAuthIdentityNotFound';
	const USER_AUTH_WRONG_PASSWORD 		= 'userAuthWrongPassword';
	const USER_AUTH_UNKNOWN_ERROR 		= 'userAuthUnknowError';
	
	/**
	 * Stores User mapper instance
	 * @var User_Model_Mapper_User
	 */
	private $_mapper;

	/**
	 * service constructor, instanciates user mapper
	 * @return void
	 */
	public function __construct()
	{
		$this->_mapper = new User_Model_Mapper_User();
	}
	
	public function testString(){
		return 'test string OK';
	}
	
	public function testArray(){
		$array = array( 'test', 'array', 'ok');
		return $array;
	}
	
public function testArray2(){
		$array = array( 'test', 'array', array( 'assoc' => 'test', 'array2'));
		return $array;
	}
	
	public function testObject(){
		$object = new stdClass();
		$object->string1 = 'test';
		$object->string2 = 'object';
		$object->string3 = 'ok';
		return $object;
	}
	
	/**
	 * Creates a user object from an array
	 * 
	 * @param array $userData
	 * @return string
	 */
	public function create( $userData )
	{
		if( !isset( $userData['login']) ||
			!isset( $userData['password']) ||
			!isset( $userData['nom']) ||
			!isset( $userData['prenom'] )){
				return self::USER_DATA_INVALID;
			}
		//return serialize($userData);
		$user = new User_Model_User();
		$user->populate( $userData );
		try{
			$id = $this->_mapper->save( $user );
			return $id;
		} catch( Exception $e ){
			return self::USER_NOT_SAVED;
		}
			
	}

	/**
	 * Finds an returns a user object given its id
	 * 
	 * @param int $id
	 * @return bool|User_Mode_User
	 */
	public function read( $id )
	{
		$user  = new User_Model_User();
		$this->_mapper->find( $id, $user );
		return $user;
	
	}

	/**
	 * Updates a user given its id
	 * @param int $id
	 * @param array $userData
	 * @return boolean|int
	 */
	public function update( $id, $userData )
	{
		$user = $this->read( $id );
		$user->setCivilite($userData['civilite'])
			 ->setEmail($userData['email'])
			 ->setNom($userData['nom'])
			 ->setPrenom($userData['prenom'])
			 ->setTelephone($userData['telephone']);
			 
		return $this->_mapper->save( $user );
	}
	
	/**
	 * Deletes a user object given its id
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function delete( $id )
	{
		return $this->_mapper->delete( $id );
	}
	
	
	/**
	 * Returns an array of user objects
	 * @return boolean|array
	 */
	public function listUsers()
	{
		return $this->_mapper->fetchAll();
	}
	
	/**
	 * Processes user authetication
	 * @param array $credentials
	 * @return string
	 */
	public function authenticate( $credentials )
	{
		$user = new User_Model_User();
		$user->setLogin( $credentials['login'])
			 ->setPassword( $credentials['password'] );
		$db = Zend_Controller_Front::getInstance()->getParam('bootstrap')
												  ->getResource('db');
					
		$authAdapter = new Zend_Auth_Adapter_DbTable( $db );
		$authAdapter->setTableName( 'user' )
					->setIdentityColumn( 'login')
					->setCredentialColumn( 'password' )
					->setIdentity( $user->getLogin() )
					->setCredential( $user->getPassword() );
		$auth       = Zend_Auth::getInstance();
		$result     = $auth->authenticate($authAdapter);

		
		switch( $result->getCode() ){
			case Zend_Auth_Result::SUCCESS :
				$userData = $authAdapter->getResultRowObject( null, 'password');
				$auth->getStorage()->write($userData);
				return self::USER_AUTH_SUCCESS;
				break;
			case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND :
			case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS :
				return self::USER_AUTH_IDENTITY_NOT_FOUND;
				break;
			case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID :
				return self::USER_AUTH_WRONG_PASSWORD;
				break;
			default:
				return self::USER_AUTH_UNKNOWN_ERROR;
				break;
		}
	
	}
	
	/**
	 * Checks wether a user has an identity
	 * @return boolean
	 */
	public function hasIdentity()
	{
		return Zend_Auth::getInstance()->hasIdentity();
	}
	
	/**
	 * Clears user's identity
	 * @return void
	 */
	public function clearIdentity()
	{
		Zend_Auth::getInstance()->clearIdentity();
	}
	
	
}

?>