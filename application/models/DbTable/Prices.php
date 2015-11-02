<?php

class Application_Model_DbTable_Prices extends Zend_Db_Table_Abstract {

    protected $_name = 'prices';
    
    // Метод для добавление новой записи
    public function addPrice($name,$price) {
    // Формируем массив вставляемых значений
        $data = array(
            'name' => $name,
            'price' => $price
        );

    // Используем метод insert для вставки записи в базу
        $this->insert($data);
    }

    public function editPrice($id, $name, $price) {
// Формируем массив вставляемых значений
        $data = array(
            'name' => $name,
            'price' => $price
        );

// Используем метод insert для вставки записи в базу
        $this->update($data, 'id=' . (int) $id);
    }

    public function getPrice($id) {
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

    public function deletePrice($id) {
        $this->delete('id=' . (int) $id);
    }
    

}

