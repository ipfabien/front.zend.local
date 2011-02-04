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
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    1.0 2010-10-10
 */

class Ip_Log extends Zend_Log
{

    /**
     * Log a message at a priority
     *
     * @param  string   $message   Message to log
     * @param  integer  $priority  Priority of message
     * @param  integer  $writerId  Specific writer id as defined in config
     * @param  mixed    $extras    Extra information to log in event
     * @return void
     * @throws Zend_Log_Exception
     */
    public function log($message, $priority, $writerId = null, $extras = null)
    {
        
        // sanity checks
        if (empty($this->_writers)) {
            /** @see Zend_Log_Exception */
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Aucun writer enregistré');
        }

        if (! isset($this->_priorities[$priority])) {
            /** @see Zend_Log_Exception */
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Priorité invalide');
        }

        // pack into event required by filters and writers
        $event = array_merge(array('timestamp'    => date('c'),
                                    'message'      => $message,
                                    'priority'     => $priority,
                                    'priorityName' => $this->_priorities[$priority]),
                              $this->_extras);

        // Check to see if any extra information was passed
        if (!empty($extras)) {
            $info = array();
            if (is_array($extras)) {
                foreach ($extras as $key => $value) {
                    if (is_string($key)) {
                        $event[$key] = $value;
                    } else {
                        $info[] = $value;
                    }
                }
            } else {
                $info = $extras;
            }
            if (!empty($info)) {
                $event['info'] = $info;
            }
        }

        // abort if rejected by the global filters
        foreach ($this->_filters as $filter) {
            if (! $filter->accept($event)) {
                return;
            }
        }
       
        // send to each writer
        if( null === $writerId ){
            foreach ($this->_writers as $writer) {
                $writer->write($event);
            }
        } else {
            $this->_writers[$writerId]->write($event);
        }
    }

/**
     * Factory to construct the logger and one or more writers
     * based on the configuration array
     *
     * @param  array|Zend_Config Array or instance of Zend_Config
     * @return Zend_Log
     */
    static public function factory($config = array())
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        }

        if (!is_array($config) || empty($config)) {
            /** @see Zend_Log_Exception */
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Configuration must be an array or instance of Zend_Config');
        }

        $log = new Ip_Log;

        if (!is_array(current($config))) {
            $log->addWriter(current($config));
        } else {
            foreach($config as $writer) {
                $log->addWriter($writer);
            }
        }

        return $log;
    }

}