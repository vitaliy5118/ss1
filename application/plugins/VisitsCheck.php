<?php
class Application_Plugin_VisitsCheck extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch() {
        
        //��������� ���������� ip ������
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        //��������� ������ � ��������� � ����
        $visitor = new Application_Model_DbTable_Visitors();
        $visitors = $visitor->fetchAll();
        $unique = true;
        
        //���������� �������� �������� � ���������� � ����
        foreach($visitors as $rows){
          if($rows['ips'] == $user_ip ){
              $visits = $rows['visits'];
              $id = $rows['id']; 
              $unique = false;
          }  
        }
        
        //���� ������� ��� ���������� ���������� ��� � ����
        if ($unique){
            $visitor->addips($user_ip);
        
        //���� ��� ������� ���������� ���������� ����������
        } else {
            $visits++; 
            $visitor->editips($id, $visits);
        }
        
    }
    
    
    
    
}
