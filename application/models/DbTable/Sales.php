<?php

class Application_Model_DbTable_Sales extends Zend_Db_Table_Abstract {

    protected $_name = 'sales';
    
    // Метод для добавление новой записи
    public function addSales($number, $name, $buyer, $status) {
    // Формируем массив вставляемых значений
        $data = array(
            'number' => $number,
            'name' => $name,
            'buyer' => $buyer,
            'status' => $status
        );

    // Используем метод insert для вставки записи в базу
        $this->insert($data);
    }

    public function editSales($id, $number, $name, $buyer, $status) {
// Формируем массив вставляемых значений
        $data = array(
            'number' => $number,
            'name' => $name,
            'buyer' => $buyer,
            'status' => $status
        );

// Используем метод insert для вставки записи в базу
        $this->update($data, 'id=' . (int) $id);
    }

    public function getSales($id) {
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

    public function deleteSales($id) {
        $this->delete('id=' . (int) $id);
    }
    
    public function searchSales($search) {
        // Формируем массив вставляемых значений
        $select = $this->select()->where('date LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('number LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('buyer LIKE ?', "%$search%");
        // Возвращаем select
        return $select;
     }

}

