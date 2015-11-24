<?php
class Application_Plugin_VisitsCheck extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch() {
        
        //считываем уникальный ip адресс
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        //принимаем данные о визитерах с базы
        $visitor = new Application_Model_DbTable_Visitors();
        $visitors = $visitor->fetchAll();
        $unique = true;
        
        //сравниваем текущего визитера с визитерами в базе
        foreach($visitors as $rows){
          if($rows['ips'] == $user_ip ){
              $visits = $rows['visits'];
              $id = $rows['id']; 
              $unique = false;
          }  
        }
        
        //если визитер был уникальный записываем его в базу
        if ($unique){
            $visitor->addips($user_ip);
        
        //если уже посещал записываем количестко просмотров
        } else {
            $visits++; 
            $visitor->editips($id, $visits);
        }
        
    }
    
    
    
    
}
