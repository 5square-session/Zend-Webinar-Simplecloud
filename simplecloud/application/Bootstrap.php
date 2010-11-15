<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initAutoloader() {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(#
            array(
                'basePath'  => APPLICATION_PATH,
                'namespace' => 'Login',
            )
        );
        $resourceLoader->addResourceType('form', 'forms/', 'Form');
    }

    public function _initCloudStorage() {
        $cloudConfig = $this->getOption('cloud');
        $cloudStorageType = $cloudConfig['storage']['type'];
        
        $credentials = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/cloud_storage.ini', $cloudStorageType, true
        );
        
        switch ($cloudStorageType) {
            case 's3':
                $credentials->aws_accesskey = getenv('AWS_ACCESSKEY');
                $credentials->aws_secretkey = getenv('AWS_SECRETKEY');
                $credentials->setReadOnly();
                break;
            case 'rackspace':
                $credentials->api_access_key = getenv('API_ACCESS_KEY');
                $credentials->api_access_username = getenv('API_ACCESS_USERNAME');
                $credentials->setReadOnly();
                break;
            default :
        }
        
        $adapter = Zend_Cloud_StorageService_Factory::getAdapter($credentials);
        Zend_Registry::set('cloudAdapter', $adapter);
    }
}

