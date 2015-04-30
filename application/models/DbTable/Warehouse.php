<?php

class Application_Model_DbTable_Warehouse extends Zend_Db_Table_Abstract {

    protected $_name = 'warehouse';
    
    // Метод для поиска значений
    public function searchWarehouse($search) {
        // Формируем массив вставляемых значений
        $select = $this->select()->where('serial = ?', $search)
                ->orwhere('name = ?', $search)
                ->orwhere('remain = ?', $search)
                ->orwhere('lastadding = ?', $search)
                ->orwhere('type = ?', $search);
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
    public function addWarehouse($serial, $name, $type, $remain, $lastadding) {
        // Формируем массив вставляемых значений
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'lastadding' => $lastadding
        );

// Используем метод insert для вставки записи в базу
        $this->insert($data);
    }
    
    public function editWarehouse($id, $serial, $name, $type, $remain, $lastadding) {
    // Формируем массив вставляемых значений
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'lastadding' => $lastadding
        );

    // Используем метод insert для вставки записи в базу
        $this->update($data, 'id=' . (int) $id);
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

