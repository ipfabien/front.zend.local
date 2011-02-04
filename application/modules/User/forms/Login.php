<?php

class User_Form_Login extends Zend_Form
{
	public function init()
	{
		$this->setAction( '/User/index/login');
			
		$decorator = array('ViewHelper',
							array( 'Label' , array( 'class' => 'label' )),
							'Errors',
							'Description',
							array( 'HtmlTag', array( 'tag' => 'p' ))
						  );
						  
		$buttonDecorator = array( 'ViewHelper',
								  array( 'HtmlTag', array( 'tag' => 'p' ))
						  );
						  
		$login = new Zend_Form_Element_Text('login');
		$login	->setLabel('Nom d\'utilisateur :')
				->setRequired(true)
				->addValidator( new Zend_Validate_Alnum() )
				->setDecorators($decorator);
				
		$password = new Zend_Form_Element_Password('password');
		$password	->setLabel('Mot de passe :')
					->setRequired(true)
					->addValidator( new Zend_Validate_Alnum() )
					->setDecorators($decorator);
					
		$submit = new Zend_Form_Element_Submit('send');
		$submit->setLabel( 'Connexion' )
			   ->setAttrib('class' , 'button' )
			   ->setDecorators($buttonDecorator);
		
		$this->addElements( array( $login, 
								   $password,
								   $submit)
						  );
						  
		$this->setDecorators( array( 'FormErrors',
									 'FormElements',
									 'Form' ) );
	}
	
}