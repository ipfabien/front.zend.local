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
 * @desc   	   Loads routes defined in routes.ini
 * @category   Ip
 * @package    Ip_Controller
 * @subpackage Plugin
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    1.0 2010-10-10
 */

class Ip_Controller_Plugin_Abstract extends Zend_Controller_Plugin_Abstract
{    

    protected function _getConfig()
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $configArray = $bootstrap->getOptions();
        return $config = new Zend_Config($configArray);
    }
    
    public function getCache()
    {
        return Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager')->getCache( 'frontcore' );
    }
    
   
}