<?php
/**
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 * 
 * @category   Application
 * @package    Bootstrap
 * @copyright  2009 Jean-Baptiste MONIN - CLEO Consulting
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: IndexController.php 03 jb $
 */
 
/** Zend_Application_Bootstrap_Bootstrap.php */
require_once 'Zend/Application/Bootstrap/Bootstrap.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initSessionRequest()
	{
		$sessionRequest = new Zend_Session_Namespace( 'sessionRequest');
		if( $sessionRequest->current ){
			$sessionRequest->previous = $sessionRequest->current;
		}
		$sessionRequest->current = $_SERVER['REQUEST_URI'];
	}
	
	
    protected function _initValidatorTranslation()
    {
        // traduction des messages d'erreur de validation
        $french = array(
	        'notAlnum' => "'%value%' ne contient pas que des lettres et/ou des chiffres.",
	        'notAlpha' => "'%value%' ne contient pas que des lettres.",
	        'notBetween' => "'%value%' n'est pas compris entre %min% et %max% inclus.",
	        'notBetweenStrict' => "'%value%' n'est pas compris entre %min% et %max% exclus.",
			'notSame' 	=> "Les deux champs ne correspondent pas.",
	        'dateNotYYYY-MM-DD'=> "'%value%' n'est pas une date au format AAAA-MM-JJ (exemple : 2000-12-31).",
	        'dateInvalid' => "'%value%' n'est pas une date valide.",
	        'dateFalseFormat' => "'%value%' n'est pas une date valide au format JJ/MM/AAAA (exemple : 31/12/2000).",
	        'notDigits' => "'%value%' ne contient pas que des chiffres.",
	        'emailAddressInvalid' => "'%value%' n'est pas une adresse mail valide au format adresse@domaine.",
	        'emailAddressInvalidFormat' => "'%value%' n'est pas une adresse mail valide au format adresse@domaine.tld",
	        'emailAddressInvalidHostname' => "'%hostname%' n'est pas un nom d'hôte DNS valide.",
	        'emailAddressInvalidMxRecord' => "'%hostname%' n'accepte pas l'adresse mail '%value%'.",
	        'emailAddressDotAtom' => "'%localPart%' ne respecte pas le format dot-atom.",
	        'emailAddressQuotedString' => "'%localPart%' ne respecte pas le format quoted-string.",
	        'emailAddressInvalidLocalPart' => "'%localPart%' n'est pas une adresse individuelle valide.",
	        'notFloat' => "'%value%' n'est pas un nombre décimal.",
	        'notGreaterThan' => "'%value%' n'est pas strictement supérieur à '%min%'.",
	        'notInt'=> "'%value%' n'est pas un nombre entier.",
	        'notLessThan' => "'%value%' n'est pas strictement inférieur à '%max%'.",
	        'isEmpty' => "Ce champ est vide : vous devez le compléter.",
	        'stringEmpty' => "Ce champ est vide : vous devez le compléter.",
	        'regexNotMatch' => "'%value%' ne respecte pas le format '%pattern%'.",
	        'stringLengthTooShort' => "'%value%' fait moins de %min% caractères.",
	        'stringLengthTooLong' => "'%value%' fait plus de %max% caractères.",
			'postcodeInvalid' => "'%value%' n'est pas du type attendu.",
	        'postcodeNoMatch' => "'%value%' n'est pas un code postal valide.",
			'notPhoneNumber' => "'%value%' ne semble pas être un numéro de téléphone français valide",
			'notIpAddress' => "'%value%' ne semble pas être une addresse IP valide"
        );

        $translate = new Zend_Translate('array', $french, 'fr_FR');
		Zend_Registry::set( 'translator', $translate );
        Zend_Validate_Abstract::setDefaultTranslator($translate);
    }
    
    private function _getView()
    {
        $_view = $this->getResource('view');
        if ( $_view instanceof Zend_View ){
            return $_view;
        }
        $this->bootstrap('view');
        return $this->getResource('view');
    }
    
    private function _getConfig()
    {
        $configArray = $this->getOptions();
        return new Zend_Config($configArray);
    }
    
}

