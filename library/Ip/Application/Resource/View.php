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
 * @category   Ip
 * @package    Application
 * @subpackage Resource
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    1.0 2010-10-10
 */
class Ip_Application_Resource_View extends Zend_Application_Resource_ResourceAbstract
{
    protected $_localOptions;
    protected $_view;
 
	
    public function init()
    {
	
		$this->_localOptions = $this->getOptions();

        # Returns view so bootstrap will store it in the registry
        if (null === $this->_view) {
            $this->_view = new Ip_View($this->_options);
        } 
		
		$this->_setupCss();
		$this->_setupJs();
		$this->_setupHeadOptions();
		
		$viewRenderer =
                Zend_Controller_Action_HelperBroker::getStaticHelper(
                    'ViewRenderer'
                );
        $viewRenderer->setView($this->_view);
		return $this->_view;
    }

	protected function _setupCss()
	{
        $this->_view->headLink()->appendStylesheet( '/css/style.css' );
        $this->_view->headLink()->appendStylesheet( '/css/print.css' , 'print');
	}
	
	protected function _setupJs()
	{
		$this->_view->headScript()->appendFile('/js/jquery-1.4.2.min.js', $type = 'text/javascript');
	}
	
	protected function _setupHeadOptions()
	{
		// Défini le charset
		$this->_view->headMeta()->setHttpEquiv( 'Content-Type', 'text/html; charset=' . $this->_localOptions['encoding'] );
		// Défini le doctype
		$this->_view->doctype($this->_localOptions['doctype']);
	}


}