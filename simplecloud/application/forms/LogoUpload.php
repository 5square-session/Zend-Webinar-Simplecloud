<?php
class Login_Form_LogoUpload extends Zend_Form {
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        
        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');

        $file = new Zend_Form_Element_File('file');
        $file->setLabel('Logo:')
                 ->setRequired(true)
                 ->addValidator('NotEmpty')
                 ->addValidator('Extension', false, 'jpg,png,gif');
             

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Upload');
        
        $this->addElements(array($file, $submit));
    } 
    
}