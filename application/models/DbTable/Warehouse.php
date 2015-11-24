<?php

class Application_Model_DbTable_Warehouse extends Zend_Db_Table_Abstract {

    protected $_name = 'warehouse';
    
    // Метод для поиска значений
    public function searchWarehouse($search) {
        // Формируем массив вставляемых значений
        $select = $this->select()->where('serial LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('remain LIKE ?', "%$search%")
               // ->orwhere('lastadding = ?', $search)
                ->orwhere('type LIKE ?', "%$search%");
        // Возвращаем select
        return $select;
    }

    //Метод выборки значений
    public function orderWarehouse($order) {
        //делаем переменную доступной
        global $settings;
        // Формируем массив вставляемых значений
        $query_part = "";
        //составляем часть для запроса из базы
        foreach ($settings['order']["$order"] as $value) {
            if ($query_part == "") {
                $query_part.= "WHERE `name` LIKE '%$value%'\n";
            } else {
                $query_part.= "OR `name` LIKE '%$value%'\n";
            }
        }
        //Составляем запрос
        $sql = (" SELECT *
                  FROM `service`
                  $query_part
                ");
        // 
        $result = $this->getAdapter()->query($sql);
        return $result;
    }

    // Метод для добавление новой записи
    public function addWarehouse($serial, $name, $type, $remain, $price, $path){
        // Формируем массив вставляемых значений
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'price' => $price,
                'path' => $path
        );

// Используем метод insert для вставки записи в базу
        $this->insert($data);
    }
    
    public function editWarehouse($id, $serial, $name, $type, $remain, $price, $path) {

    // Формируем массив вставляемых значений
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'path' => $path
        );
        
        if ($price!='unload'){
            $data['price'] = $price;
            
        }
        
    // Используем метод insert для вставки записи в базу
        $this->update($data, 'id=' . (int) $id);
                //Составляем запрос
        $sql = (" UPDATE warehouse
                  SET lastadding = CURRENT_TIMESTAMP()
                  WHERE id = $id
                ");

        $this->getAdapter()->query($sql);
    }
    
    public function loadWarehouse($id, $remain, $price) {
        
       //Составляем запрос
        $sql = (" UPDATE warehouse
                  SET lastadding = CURRENT_TIMESTAMP(), 
                      remain = $remain,
                      price = $price
                  WHERE id = $id
                ");

        $this->getAdapter()->query($sql);
    }

    public function getWarehouse($id) {
// Получаем id как параметр
        $id = (int) $id;

// Используем метод fetchRow для получения записи из базы.
// В скобках указываем условие выборки (привычное для вас where)
        $row = $this->fetchRow('id = ' . $id);

// Если результат пустой, выкидываем исключение
        if (!$row) {
            throw new Exception("Нет записи с id - $id");
        }
// Возвращаем результат, упакованный в массив
        return $row->toArray();
    }
    
        public function deleteWarehouse($id) {
        $this->delete('id=' . (int) $id);
    }
 
}

