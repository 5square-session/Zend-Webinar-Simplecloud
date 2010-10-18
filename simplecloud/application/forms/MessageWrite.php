<?php
class Login_Form_MessageWrite extends Zend_Form {
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        
        $this->setName('message_write');
        
        $message = new Zend_Form_Element_Text('message');
        $message->setLabel('Write Message:'); 

        $submit = new Zend_Form_Element_Submit('submit_write');
        $submit->setLabel('Write');
        
        $this->addElements(array($message, $submit));
    } 
    
}