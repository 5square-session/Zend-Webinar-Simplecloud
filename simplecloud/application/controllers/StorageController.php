<?php

class StorageController extends Zend_Controller_Action
{
    /**
     * @var Zend_Cloud_StorageService_Adapter_S3
     */
    private $adapter = null;

    public function init()
    {
        $credentials = array(
            'http_adapter' => "Zend_Http_Client_Adapter_Socket",
            'storage_adapter' => "Zend_Cloud_StorageService_Adapter_S3",
            'bucket_name' => "zend-webinar-de-bucket",
            'aws_accesskey' => getenv('AWS_ACCESSKEY'),
            'aws_secretkey' => getenv('AWS_SECRETKEY')
        );
        $this->adapter = Zend_Cloud_StorageService_Factory::getAdapter($credentials);
    }

    public function indexAction()
    {
        $this->_forward('list');
    }
    
    public function listAction() {
        $logoItems = $this->adapter->listItems();
        $logos = array();
        foreach ($logoItems as $itemName) {
            $logos[$itemName] = base64_encode($this->adapter->fetchItem($itemName));
        }
        
        $this->view->logos = $logos;
        
        $form = new Login_Form_LogoUpload();
        
        if ($this->getRequest()->isPost()) {
            $filename = $form->file->getFileName();
            $this->adapter->storeItem($form->file->getValue(), file_get_contents($filename));
            
            $this->_redirect('/storage');
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $itemName = $this->_getParam('itemname');
        
        $this->adapter->deleteItem($itemName);
        
        $this->_redirect('/storage');
    }

}

