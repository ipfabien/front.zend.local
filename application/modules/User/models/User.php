<?php

class User_Model_User{
	private $_login;
	private $_password;
	private $_nom;
	private $_prenom;
	private $_email;
	private $_civilite;
	private $_telephone;
	
	public function __construct( Zend_Db_Table_Row $row = null ){
		if( $row !== null)
			$this->populate($row);
	}
	public function getId(){
		return $this->_id;
	}
	
	public function setId($id){
		$this->_id = $id;
		return $this;
	}

	public function getLogin(){
		return $this->_login;
	}
	
	public function setLogin($login){
		$this->_login = $login;
		return $this;
	}
	
	public function getNom(){
		return $this->_nom;
	}
	
	public function setNom($nom){
		$this->_nom = $nom;
		return $this;
	}
	
	public function getPrenom(){
		return $this->_prenom;
	}
	
	public function setPrenom($prenom){
		$this->_prenom = $prenom;
		return $this;
	}
	
	public function getEmail(){
		return $this->_email;
	}
	
	public function setEmail($email){
		$this->_email = $email;
		return $this;
	}
	
	public function getcivilite(){
		return $this->_civilite;
	}
	
	public function setCivilite($civilite){
		$this->_civilite = $civilite;
		return $this;
	}
	
	public function geTtelephone(){
		return $this->_telephone;
	}
	
	public function setTelephone($telephone){
		$this->_telephone = $telephone;
		return $this;
	}

	public function getPassword(){
		return $this->_password;
	}
	
	public function setPassword($password){
		$this->_password = sha1($password);
		return $this;
	}
	
	public function populate($row){
		$this->setId( $row['id'] )
			 ->setLogin( $row['login'] )
			 ->setPassword( $row['password'] )
			 ->setNom( $row['nom'] )
			 ->setPrenom( $row['prenom'] )
			 ->setEmail( $row['email'] )
			 ->setCivilite( $row['civilite'] )
			 ->setTelephone( $row['telephone'] );
	}
}