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
 * @desc   	   Validates a phone number in french format
 * @category   Ip
 * @package    Validate
 * @subpackage PhoneNumber
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    1.0 2010-10-10
 */
class Ip_Validate_PhoneNumber_French extends Zend_Validate_Abstract
{
    const INVALID        	= 'phoneNumberInvalid';
    const NOT_PHONE_NUMBER 	= 'notPhoneNumber';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID          => "Donnée invalide, chaîne attendue",
        self::NOT_PHONE_NUMBER => "'%value%' ne semble pas être un numéro de téléphone valide",
    );

    /**
     * internal options
     *
     * @var array
     */
    protected $_options = array(
        'allowinternational' => true,
        'allowpoints' => true,
        'allowhyphens' => true,
        'allowspaces' => true,
    );

    /**
     * Sets validator options
     *
     * @param array $options OPTIONAL Options to set
     * @return void
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Returns all set options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Sets the options for this validator
     *
     * @param array $options
     * @return Zend_Validate_Ip
     */
    public function setOptions($options)
    {
        if (array_key_exists('allowinternational', $options)) {
            $this->_options['allowinternational'] = (boolean) $options['allowinternational'];
        }

        if (array_key_exists('allowpoints', $options)) {
            $this->_options['allowpoints'] = (boolean) $options['allowpoints'];
        }
		
		if (array_key_exists('allowhyphens', $options)) {
            $this->_options['allowhyphens'] = (boolean) $options['allowhyphens'];
        }
		
		if (array_key_exists('allowspaces', $options)) {
            $this->_options['allowspaces'] = (boolean) $options['allowspaces'];
        }

        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid phone number 
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->_error(self::INVALID);
            return false;
        }
		
        $this->_setValue($value);
		
		
        if (!$this->_validatePhoneNumber($value)) {
            $this->_error(self::NOT_PHONE_NUMBER);
            return false;
        }

        return true;
    }

    /**
     * Validates a phone number in french format
     *
     * @param  string $value Value to check against
     * @return boolean True when $value is a valid phone number
     *                 False otherwise
     */
    protected function _validatePhoneNumber($value) 
	{
		if (!$this->_options['allowspaces'] && strpos($value, ' ')) {
            return false;
        } else {
            $value = str_replace(' ', '', $value );
        }
			
		if (!$this->_options['allowhyphens'] && strpos($value, '-')) {
            return false;
        } else {
            $value = str_replace('-', '', $value );
        }
		
		if (!$this->_options['allowpoints'] && strpos($value, '.')) {
            return false;
        } else {
            $value = str_replace('.', '', $value );
        }
		
		if (!$this->_options['allowinternational'] && preg_match('/^\+33/', $value)) {
            return false;
        } else {
            $value = str_replace('+33', '0', $value );
        }

        return preg_match('/^[0-9]{10}$/', $value);

    }
}
