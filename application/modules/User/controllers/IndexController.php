<?php
class User_IndexController extends Ip_Controller_Action
{
	private $_messages = null;
	
	public function init()
	{
	}
    /**
     * indexAction
     *
     * @return void
     */
     public function indexAction(){
		
	 }
	 
    /**
     *viewAction
     *
     * @return void
     */
     public function viewAction()
	 {
		$id 				= (int) $this->getRequest()->getParam('id');
		$userService 		= new User_Service_User();
		$this->view->user 	= $userService->read($id);
	 }
	 
	/**
     *listAction
     *
     * @return void
     */
     public function listAction()
	 {
		$userService 	= new User_Service_User();
		$list 			= $userService->listUsers();
		$this->view->users = $list;
	 }

	/**
	*deleteAction
          *
          * @return void
          */
     public function deleteAction()
	 {
		$id 		= (int) $this->getRequest()->getParam('id');
		$userService = new User_Service_User();
		
		if( $userService->delete( $id ) ){
			$this->addSystemSuccess('Suppression OK');
		} else {
			$this->addSystemError('Suppression Ko');
		}
		
		$this->_redirect( $this->_helper->url->url( array(), 'userList' ));
	 }
	 
	 /**
	*adduserAction
          *
          * @return void
          */
     public function adduserAction()
	 {
	 	$userService = new User_Service_User();
		$form = new User_Form_Adduser();
		
		if( $this->getRequest()->isPost() ){
			if( $form->isValid( $this->getRequest()->getPost() ) ){
				$result = $userService->create( $form->getValues() );
				switch( $result ){
					case User_Service_User::USER_DATA_INVALID:
						$this->addSystemError('Données invalides');
						break;
					case User_Service_User::USER_NOT_SAVED:
						$this->addSystemError('Echec de la création');
						break;
					default:
						$this->addSystemSuccess('Utilisateur créé');
						$form->reset();
						$this->_redirect( $this->_helper->url->url( array('id' => $result), 'userById' ));
						break;
				}
			} else {
				$this->addSystemError('Le formulaire contient des erreurs');
			}
		}
		$this->view->form = $form;
	 }
	 
	 /**
	  * Enter description here ...
	  */
	 public function editAction()
	 {
	 	$id 			= (int) $this->getRequest()->getParam('id');
	  	$userService 	= new User_Service_User();
	  	$user			= $userService->read( $id );
	  	if( !$user instanceof User_Model_User){
	  		$this->addSystemError('L\'utilisateur n\'existe pas');
	  		$this->_redirect( '/User/index/list');
	  	}
	  	$form 			= new User_Form_Edituser();
	  	$form->setAction( '/User/index/edit/id/' . $id);
	 	if( $this->getRequest()->isPost() ){
	 		if( $form->isValid( $this->getRequest()->getPost() ) ){
				if( $userService->update( $id, $form->getValues() )){
					$this->addSystemSuccess('Utilisateur mis à jour');
				} else {
					$this->addSystemError('Echec de la mise à jour');
				}
	 		} else {
	 			$this->addSystemError('Le formulaire contient des erreurs');
	 		}
	 	} else {
			$userData 		= array( 'login' => $user->getLogin(),
									 'nom' => $user->getNom(),
									 'prenom' => $user->getPrenom(),
									 'email' => $user->getEmail(),
									 'telephone' => $user->geTtelephone(),
									 'civilite' => $user->getcivilite() 
									);
			$form->populate( $userData );
	 	}
		$this->view->form = $form;
	 }
	 
	 
	  /**
	*loginAction
          *
          * @return void
          */
     public function loginAction()
	 {
	 	
	 	$userService = new User_Service_User();
	 	
		if( $userService->hasIdentity() ){
			$this->renderScript( 'index/logedin.phtml' );
		} else {
			$sessionRequest = new Zend_Session_Namespace( 'sessionRequest');
			$form = new User_Form_Login();
			if( $this->getRequest()->isPost() ){
				if( $form->isValid( $this->getRequest()->getPost() ) ){
					$result = $userService->authenticate( $this->getRequest()->getPost());
					switch( $result ){
						case User_Service_User::USER_AUTH_SUCCESS :
							$this->addSystemSuccess('Connecté');
							$form->reset();
							$this->_redirect( $sessionRequest->previous);
							break;
						default :
							$this->addSystemError('Echec de la connexion');
							$this->_redirect($sessionRequest->previous);
							break;
					}
				} else {
					$errors = $form->getErrors();
					$translate = Zend_Registry::get( 'translator');
					$errorMessage = 'Le formulaire contient des erreurs : <br />';
					foreach( $errors as $fieldName => $fieldErrors ){
						foreach( $fieldErrors as $error ){
							$errorMessage .= 'Champ ' . $fieldName . ' : ' . $translate->_($error) . '<br />';
						}
					}
					$this->addSystemError($errorMessage);
					$this->_redirect($sessionRequest->previous);
				}
			}
			$this->view->form = $form;
		}
	 }
	 
	   /**
	*logoutAction
          *
          * @return void
          */
     public function logoutAction()
	 {
	 	$userService = new User_Service_User();
		$userService->clearIdentity();
		$this->_redirect( $this->_helper->url->url( array(), 'index' ));
	 }
}














