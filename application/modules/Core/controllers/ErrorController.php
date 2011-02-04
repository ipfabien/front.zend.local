<?php
class ErrorController extends Ip_Controller_Action
{

    private $_error;
    private $_messageShort;
    private $_messageLong;
    private $_trace;
    private $_httpResponseCode;
    private $_logLevel;
    
    public function postDispatch()
    {
       $this->view->headTitle( $this->_httpResponseCode . ' - Erreur de l\'application : ' . $this->_messageShort );
    }
    
    public function errorAction()
    {
        $this->_helper->layout->setLayout('error');
        
        $this->_error                 = $this->_getParam('error_handler');    
        $this->_messageLong           = $this->_error->exception->getMessage();        
        
        switch ($this->_error->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                    $this->_httpResponseCode    = 404;
                    $this->_messageShort        = 'Page inexistante';
                    $this->_logLevel            = Zend_Log::ALERT;
                break;
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                    $this->_logLevel            = Zend_Log::ALERT;
					$this->_httpResponseCode    = 500;
                    $this->_messageShort   		= 'Erreur interne';
                break;
            }

        $this->getResponse()->setHttpResponseCode($this->_httpResponseCode);
        $this->view->exception      = $this->_error;
        $this->view->errorCode      = $this->_httpResponseCode;
        $this->view->errorMessage   = $this->_messageShort;
        $this->view->errorMessage2  = $this->_messageLong;
        
        // Uses file log if set up
        if( $log = $this->getLog() ){
            $log->log( $this->_messageLong , $this->_logLevel );
        }
    }
}