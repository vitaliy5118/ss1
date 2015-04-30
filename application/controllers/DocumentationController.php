<?php

class DocumentationController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
     
      $form = new Application_Form_Documentation();
      $data = new Application_Model_DbTable_Data();
      $form->submit->setLabel('Загрузить');
      
      if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
 
                // success - do something with the uploaded file
                $uploadedData = $form->getValues();
                $fullFilePath = $form->file->getFileName();
                
                $needle   = '/public/data';
                $pos      = strripos($fullFilePath, $needle);
                
                $filename = substr($fullFilePath, $pos+13);
                
                $data->addData($uploadedData['description'], $filename);
            } else {
                $form->populate($formData);
            }
        }
        
      $this->view->data = $data->fetchAll()->toArray();
      $this->view->form = $form;
        
    }
    
    public function deleteAction(){
        $id = $this->getRequest()->getParam('id');
        $name = $this->getRequest()->getParam('name');
               
       if (file_exists(DATA_PATH . '/public/data/'. $name)){
            $data = new Application_Model_DbTable_Data();
            $data->deleteData($id);
            unlink(DATA_PATH . '/public/data/'. $name);
       } else {
            $data = new Application_Model_DbTable_Data();
            $data->deleteData($id);
           echo ('Файл не существует!');
           die;
       }
       
       
       $this->_helper->redirector('index');
    }

}