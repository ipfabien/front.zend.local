<?php

class User_Form_Edituser extends Zend_Form
{
	public function init()
	{
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
	
		$submit = new Zend_Form_Element_Submit('send');

		
		$this->addElements( array(
								   $civilite,
								   $nom, 
								   $prenom,
								   $email,
								   $tel,
								   $submit)
						  );
						  
	}
	
}