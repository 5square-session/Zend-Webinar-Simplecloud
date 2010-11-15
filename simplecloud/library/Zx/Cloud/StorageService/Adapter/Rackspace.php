<?php
require_once 'Zend/Cloud/StorageService/Adapter.php';
require_once 'Zend/Cloud/StorageService/Exception.php';

class Zx_Cloud_StorageService_Adapter_Rackspace 
    implements Zend_Cloud_StorageService_Adapter
{

    const CONTAINER_NAME      = 'container';
    
    const API_ACCESS_KEY      = 'api_access_key';
    const API_ACCESS_USERNAME = 'api_access_username';

    protected $_rackspace;
    protected $_defaultContainerName = null;

    public function __construct($options = array()) 
    {
        require_once 'cloudfiles.php';
        
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (!is_array($options)) {
            throw new Zend_Cloud_StorageService_Exception('Invalid options provided');
        }

        if (!isset($options[self::API_ACCESS_KEY]) || !isset($options[self::API_ACCESS_USERNAME])) {
            throw new Zend_Cloud_StorageService_Exception('API Access keys not specified!');
        }
        
        if (isset($options[self::CONTAINER_NAME])) {
            $this->_defaultContainerName = $options[self::CONTAINER_NAME];
        }
        
        try {
            $auth = new CF_Authentication(
                $options[self::API_ACCESS_USERNAME],
                $options[self::API_ACCESS_KEY]
            );
            $auth->ssl_use_cabundle();
            $auth->authenticate();
            $conn = new CF_Connection($auth);
            $conn->ssl_use_cabundle();
            $this->_rackspace = $conn->get_container($this->_defaultContainerName);
            
        } catch (Exception $e) { 
            throw new Zend_Cloud_StorageService_Exception('Error on create: '.$e->getMessage(), $e->getCode(), $e);
        }

    }

    public function fetchItem($path, $options = array()) 
    {
        try {
            $cfObject = $this->_rackspace->get_object($path);
            return $cfObject->read();
        } catch (Exception  $e) { 
            throw new Zend_Cloud_StorageService_Exception('Error on fetch: '.$e->getMessage(), $e->getCode(), $e);
        }
    }

    public function storeItem($destinationPath, $data, $options = array()) 
    {
        try {
            $cfObject = $this->_rackspace->create_object($destinationPath);
            $cfObject->write($data);
        } catch (Exception  $e) { 
            throw new Zend_Cloud_StorageService_Exception('Error on store: '.$e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteItem($path, $options = array()) 
    {    
        try {
            $this->_rackspace->delete_object($path);
        } catch (Exception  $e) { 
            throw new Zend_Cloud_StorageService_Exception('Error on delete: '.$e->getMessage(), $e->getCode(), $e);
        }
    }
    
    public function listItems($path, $options = null) 
    {
        try {
            return $this->_rackspace->list_objects($this->_defaultContainerName);
        } catch (Exception  $e) { 
            throw new Zend_Cloud_StorageService_Exception('Error on list: '.$e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get the concrete client.
     * @return CF_Container
     */
    public function getClient()
    {
        return $this->_rackspace;       
    }
    
    public function copyItem($sourcePath, $destinationPath, $options = null) {
        // TODO: implement Zx_Cloud_StorageService_Adapter_Rackspace::copyItem()
        throw new Zend_Cloud_StorageService_Exception(
        	'Not implemented yet: Zx_Cloud_StorageService_Adapter_Rackspace::copyItem()'
        );   
    }

    public function moveItem($sourcePath, $destinationPath, $options = null) {
        // TODO: implement Zx_Cloud_StorageService_Adapter_Rackspace::moveItem()
        throw new Zend_Cloud_StorageService_Exception(
        	'Not implemented yet: Zx_Cloud_StorageService_Adapter_Rackspace::moveItem()'
        );   
    }

    public function renameItem($path, $name, $options = null) {
        // TODO: implement Zx_Cloud_StorageService_Adapter_Rackspace::renameItem()
        throw new Zend_Cloud_StorageService_Exception(
        	'Not implemented yet: Zx_Cloud_StorageService_Adapter_Rackspace::renameItem()'
        );   
    }
    
    public function fetchMetadata($path, $options = null) {
        // TODO: implement Zx_Cloud_StorageService_Adapter_Rackspace::fetchMetadata()
        throw new Zend_Cloud_StorageService_Exception(
        	'Not implemented yet: Zx_Cloud_StorageService_Adapter_Rackspace::fetchMetadata()'
        );   
    }

    public function storeMetadata($destinationPath, $metadata, $options = null) {
        // TODO: implement Zx_Cloud_StorageService_Adapter_Rackspace::storeMetadata()
        throw new Zend_Cloud_StorageService_Exception(
        	'Not implemented yet: Zx_Cloud_StorageService_Adapter_Rackspace::storeMetadata()'
        );   
    }

    public function deleteMetadata($path) {
        // TODO: implement Zx_Cloud_StorageService_Adapter_Rackspace::deleteMetadata()
        throw new Zend_Cloud_StorageService_Exception(
        	'Not implemented yet: Zx_Cloud_StorageService_Adapter_Rackspace::deleteMetadata()'
        );   
    }
}
