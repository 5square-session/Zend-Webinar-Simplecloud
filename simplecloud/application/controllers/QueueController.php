<?php

class QueueController extends Zend_Controller_Action
{
    const QUEUE_NAME = 'ZendWebinarMessageQueue';
    
	/**
     * @var Zend_Cloud_QueueService_Adapter_Sqs
     */
    private $adapter = null;
    
    private $queueId = null;

    public function init()
    {
        $credentials = array(
            'http_adapter' => "Zend_Http_Client_Adapter_Socket",
            'queue_adapter' => "Zend_Cloud_QueueService_Adapter_Sqs",
            'aws_accesskey' => getenv('AWS_ACCESSKEY'),
            'aws_secretkey' => getenv('AWS_SECRETKEY')
        );
        $this->adapter = Zend_Cloud_QueueService_Factory::getAdapter($credentials);
    }
    
    public function preDispatch() {
        foreach ($this->adapter->listQueues() as $queueId) {
            if (substr($queueId, -(strlen(self::QUEUE_NAME))) == (self::QUEUE_NAME)) {
                $this->queueId = $queueId;
                break;
            }
        }
        if (!$this->queueId) {
            $this->queueId = $this->adapter->createQueue(self::QUEUE_NAME);
        }
    }

    public function indexAction()
    {
        $readForm = new Login_Form_MessageRead();
        $writeForm = new Login_Form_MessageWrite();
        
        if ($this->getRequest()->isPost()) {
            if ($this->_hasParam('submit_read')
                && $readForm->isValid($this->_getAllParams())) {
                $messageSet = $this->adapter->receiveMessages($this->queueId);
                
                if (count($messageSet) == 0) {
                    $this->view->nothingToRead = true;
                    $readForm->message->setValue('---');
                }
                else {
                	/* @var $message Zend_Cloud_QueueService_Message */
                    $messageItem = $messageSet[0];
                    $readForm->message->setValue($messageItem->getBody());
                    
                    $this->adapter->deleteMessage($this->queueId, $messageItem);
                }
            }
            elseif ($this->_hasParam('submit_write') 
                    && $writeForm->isValid($this->_getAllParams())) {
                $message = $writeForm->message->getValue();                        
                $this->adapter->sendMessage($this->queueId, $message);
                $this->view->messageWrite = true;
            }
        }
        $this->view->readForm = $readForm;
        $this->view->writeForm = $writeForm;
    }
    
    public function deleteQueuesAction() {
        foreach ($this->adapter->listQueues() as $queueId) {
            $this->adapter->deleteQueue($queueId);
        }
    }
}

