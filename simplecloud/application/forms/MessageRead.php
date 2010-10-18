<?php
class Login_Form_MessageRead extends Zend_Form {
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        
        $this->setName('message_read');   
        
        $message = new Zend_Form_Element_Text('message');
        $message->setLabel('Read Message:')
                ->setAttrib('readonly', 'true');

        $submit = new Zend_Form_Element_Submit('submit_read');
        $submit->setLabel('Read');
        
        $this->addElements(array($message, $submit));
    } 
    
}