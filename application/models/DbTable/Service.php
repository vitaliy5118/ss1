<?php

class Application_Model_DbTable_Service extends Zend_Db_Table_Abstract {

    protected $_name = 'service';

    //Метод выборки значений
    public function showService($date) {

        //Составляем запрос
        $sql = (" SELECT *
                  FROM `service` 
                  WHERE `date` LIKE '%$date%'
                  ORDER BY date DESC
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

    // Метод для добавление новой записи
    public function addService($client, $number, $claim, $diagnos, $spares, $work, $status, $name, $comments, $counter, $serialize_price, $serialize_data) {
        // Формируем массив вставляемых значений
        $data = array(
            'client' => $client,
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'spares' => $spares,
            'work' => $work,
            'status' => $status,
            'name' => $name,
            'comments' => $comments,
            'counter' => $counter,
            'serialize_price' => $serialize_price,
            'serialize_data' => $serialize_data,
        );

        // Используем метод insert для вставки записи в базу
        $this->insert($data);
    }
    // Метод для добавление новой записи
    public function editService($id, $client, $number, $claim, $diagnos, $spares, $work, $status, $name, $comments, $counter, $serialize_price, $serialize_data) {
        // Формируем массив вставляемых значений
        $data = array(
            'client' => $client,
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'spares' => $spares,
            'work' => $work,
            'status' => $status,
            'name' => $name,
            'comments' => $comments,
            'counter' => $counter,
            'serialize_price' => $serialize_price,
            'serialize_data' => $serialize_data,
        );

    // Используем метод insert для вставки записи в базу
        $this->update($data, 'id=' . (int) $id);
    }

    public function getService($id) {
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

    public function searchService($search) {
        // Формируем массив вставляемых значений
        $select = $this->select()->where('claim LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('number LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('client LIKE ?', "%$search%")
                ->orwhere('diagnos LIKE ?', "%$search%")
                ->orwhere('work LIKE ?', "%$search%")
                ->orwhere('spares LIKE ?', "%$search%")
                ->orwhere('comments LIKE ?', "%$search%")
                ->orwhere('counter LIKE ?', "%$search%");

        // Возвращаем select
        return $this->fetchAll($select);
    }

    public function getcountService($date = null) {

        //Составляем запрос
        $sql = (" SELECT count(id) as count
                  FROM `service`
                  WHERE `date` LIKE '%$date%'
                ");
        // 

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    public function getcountsearchService($search) {

        //Составляем запрос
        $sql = (" SELECT count(id) as count
                  FROM `service`
                  WHERE `name` LIKE '%$search%'
                     OR `number` LIKE '%$search%'
                     OR `status` LIKE '%$search%'
                     OR `client` LIKE '%$search%'
                     OR `diagnos` LIKE '%$search%'
                     OR `work` LIKE '%$search%'
                     OR `spares` LIKE '%$search%'
                     OR `comments` LIKE '%$search%'
                     OR `counter` LIKE '%$search%'
                ");
        // 

        return $this->getAdapter()->query($sql)->fetchAll();
    }

}
