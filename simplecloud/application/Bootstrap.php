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

}

