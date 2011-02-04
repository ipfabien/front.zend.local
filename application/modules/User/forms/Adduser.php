<?php

class User_Form_Adduser extends Zend_Form
{
	public function init()
	{
		$this->setAction( '/User/index/adduser');
						  
		$login = new Zend_Form_Element_Text('login');
		$login	->setRequired(true)
				->addValidator( new Zend_Validate_Alnum() );
				
		$password = new Zend_Form_Element_Password('password');
		$password	->setRequired(true)
					->addValidator( new Zend_Validate_Alnum() );
					
		$password2 = new Zend_Form_Element_Password('password2');
		$password2	->setRequired(true)
					->addValidator( new Zend_Validate_Alnum() )
					->addValidator( new Zend_Validate_Identical('password') );
		
		$nom = new Zend_Form_Element_Text('nom');
		$nom	->setRequired(true)
				->addValidator( new Zend_Validate_Alnum() );
		
		$prenom = new Zend_Form_Element_Text('prenom');
		$prenom	->setRequired(true)
				->addValidator( new Zend_Validate_Alnum() );
				
		$email = new Zend_Form_Element_Text('email');
		$email	->setRequired(true)
				->addValidator( new Zend_Validate_EmailAddress() );
				
		$tel = new Zend_Form_Element_Text('telephone');
		$tel	->setRequired(true)
				->addValidator( new Ip_Validate_PhoneNumber_French() );
		 		
		$civArray  = array( 'M' => 'M.', 
						    'Mme' => 'Mme.',
							'Melle' => 'Melle.');
		$civilite = new Zend_Form_Element_Select('civilite');
		$civilite->setMultiOptions( $civArray )
				 ->setRequired(true);
				
		 $params = array( 'ssl' => false,
						   'error' => null,
						   'xhtml' => true
						 );
		 $options = array( 'theme' => 'white',
						   'lang' => 'fr'
						);
						
		$publicKey 	= '6LdoLcASAAAAAAFm5pnMOnHA2KjkI6KctRBBOfNS';
		$privateKey = '6LdoLcASAAAAABb0_ZBSFJdByFzFS6YzskE3BMJ8';
	    $reCaptcha 	= new Zend_Service_ReCaptcha( $publicKey, $privateKey,$params, $options );
											  
		 $adapter   = new Zend_Captcha_ReCaptcha();
		 $adapter->setService( $reCaptcha );

		 $captcha = new Zend_Form_Element_Captcha('captcha', 
		 array( 'label' => 'vérification d\'humanitude', 'captcha' => $adapter ));

		
		$submit = new Zend_Form_Element_Submit('send');

		
		$this->addElements( array( $login, 
								   $password,
								   $password2,
								   $civilite,
								   $nom, 
								   $prenom,
								   $email,
								   $tel,
								   $submit)
						  );
						  
	}
	
}