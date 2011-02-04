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
 * @package    View
 * @subpackage Helper
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    1.0 2010-10-10
 */

class Ip_View_Helper_DisplayMessages extends Zend_View_Helper_Abstract
{

    /**
     * Session namespace
     * @var string
     */
    protected $_sessionNamespace = 'Ip_System_Messages';
    
  /**
     * displayMessages
     *
     * @return string
     */
    function displayMessages()
    {
        // retrieves stored system messages
        $messages = new Zend_Session_Namespace($this->_sessionNamespace);
        
        // for each type of messages, display if not empty
        if ( is_array($messages->success) && 0 != count($messages->success) ) {
            print '<p class="success">';
            foreach( $messages->success as $message ){
                print $message . '<br />';
            }
            print '</p>';
            $messages->success = array();
        }
        if (  is_array($messages->warning) && 0 != count($messages->warning) ) {
            print '<p class="warning">';
            foreach( $messages->warning as $message ){
                print $message . '<br />';
            }
            print '</p>';
            $messages->warning = array();
        }
        if ( is_array($messages->error) && 0 != count($messages->error) ) {
            print '<p class="error">';
            foreach( $messages->error as $message ){
                print $message . '<br />';
            }
            print '</p>';
            $messages->error = array();
        }

    }
}