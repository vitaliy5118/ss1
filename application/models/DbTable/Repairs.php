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
        $sql = (" SELECT *
                  FROM `repairs`
                  WHERE `date` LIKE '%$date%'
                      
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
    
    // Метод для добавление новой записи
    public function addRepaire($number, $claim, $diagnos, $spares, $work, $comments) {
        // Формируем массив вставляемых значений
        $data = array(
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'work' => $work,
            'spares' => $spares,
            'comments' => $comments
        );

// Используем метод insert для вставки записи в базу
        $this->insert($data);
    }
    
    public function editRepaire($id, $number, $data, $claim, $diagnos, $spares, $work, $comments) {
    // Формируем массив вставляемых значений
        $data = array(
            'data' => $data,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'work' => $work,
            'spares' => $spares,
            'comments' => $comments,
            'number' => $number
        );

    // Используем метод insert для вставки записи в базу
        $this->update($data, 'id=' . (int) $id);
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

