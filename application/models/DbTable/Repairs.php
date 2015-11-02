<?php

class Application_Model_DbTable_Repairs extends Zend_Db_Table_Abstract {

    protected $_name = 'repairs';

    // Метод для поиска значений
    public function searchRepairs($search, $id) {
        // Формируем массив вставляемых значений
        $select = $this->select($id)->where('data = ?', $search)
                ->orwhere('claim = ?', $search)
                ->orwhere('diagnos = ?', $search)
                ->orwhere('work = ?', $search)
                ->orwhere('spares = ?', $search)
                ->orwhere('comments = ?', $search);
        // Возвращаем select
        return $select;
    }
    
    //Метод выборки значений
    public function statisticRepairs($date) {

        //Составляем запрос
        $sql = (" SELECT repairs.*, devices.name
                  FROM `repairs` JOIN `devices` ON repairs.number=devices.number
                  WHERE `date` LIKE '%$date%'
                  ORDER BY date DESC
                      
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    //Метод выборки значений
    public function getcountRepairs($date = null) {

        //Составляем запрос
        $sql = (" SELECT count(id) as count
                  FROM `repairs`
                  WHERE `date` LIKE '%$date%'
                ");
        // 

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    //Метод выборки значений
    public function getCountRepairsbyName($name) {

        //Составляем запрос
        $sql = (" SELECT count(repairs.id) as count
                  FROM `repairs` JOIN `devices` ON repairs.number=devices.number 
                  WHERE `name` LIKE '%$name%'
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    public function getCountRepairsbyNumber($number) {

        //Составляем запрос
        $sql = (" SELECT count(repairs.id) as count
                  FROM `repairs` 
                  WHERE `number` = '$number'
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    
    // Метод для добавление новой записи
    public function addRepaire($number, $claim, $diagnos, $spares, $work, $comments, $counter, $serialize_data, $serialize_checked) {
        // Формируем массив вставляемых значений
        $data_array = array(
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'work' => $work,
            'spares' => $spares,
            'comments' => $comments,
            'counter' => $counter,
            'serialize_data' => $serialize_data,
            'serialize_checked' => $serialize_checked
        );

// Используем метод insert для вставки записи в базу
        $this->insert($data_array);
    }
    
    public function editRepaire($id, $number, $claim, $diagnos, $spares, $work, $comments, $counter, $serialize_data, $serialize_checked) {
    // Формируем массив вставляемых значений
        $data_array = array(
            'claim' => $claim,
            'diagnos' => $diagnos,
            'work' => $work,
            'spares' => $spares,
            'comments' => $comments,
            'number' => $number,
            'counter' => $counter,
            'serialize_data' => $serialize_data,
            'serialize_checked' => $serialize_checked
        );

    // Используем метод insert для вставки записи в базу
        $this->update($data_array, 'id=' . (int) $id);
    }

    public function getRepaire($id) {
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
    
        public function deleteRepaire($id) {
        $this->delete('id=' . (int) $id);
    }

}

