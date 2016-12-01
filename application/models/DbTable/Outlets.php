<?php

class Application_Model_DbTable_Outlets extends Zend_Db_Table_Abstract {

    protected $_name = 'outlets';

    public function addDevice(Application_Model_Device $device) {
        // сохран€ем данные в таблицу
        $this->insert($device->makearray());
    }

    public function editDevice(Application_Model_Device $device) {

        // редактируем запись
        $this->update($device->makearray(), 'id=' . (int) $device->id);
    }

    public function editDeviceStatus($number, $status) {
        // ????????? ?????? ??????????? ????????
        $data = array(
            'status' => $status,
        );
        // ?????????? ????? insert ??? ??????? ?????? ? ????
        $this->update($data, 'number=' . "'$number'");
    }

    public function getDevice($id) {
        //принимаем id
        $id = (int) $id;
        //проверка данных
        $row = $this->fetchRow('id=' . $id);
        if (!$row) {
           echo ("ќшибка!<br> «апись с идентификатором id=$id не существет!"); die; 
        }

        //возвращаем массив
        return $row->toArray();
    }

    public function getName($number) {
        //?????? ?????? ? ???????
        $row = $this->fetchRow('number =' . "'$number'");
        if (!row) {
            throw new Exeption("??? ?????? ? ??????? - $number");
        }
        //?????????? ????????? ? ???? ???????
        return $row->toArray();
    }

    public function deleteDevice($id) {
        //??????? ?????? ? ???????
        $this->delete('id=' . (int) $id);
    }

    public function searchDevice($search) {
        // ????????? ?????? ??????????? ????????
        $select = $this->select()->where('number LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('owner LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('user LIKE ?', "%$search%")
                ->orwhere('city LIKE ?', "%$search%")
                ->orwhere('adress LIKE ?', "%$search%")
                ->orwhere('tt_name LIKE ?', "%$search%")
                ->orwhere('tt_user LIKE ?', "%$search%")
                ->orwhere('tt_phone LIKE ?', "%$search%");

        // ?????????? select
        return $select;
    }

    //????? ??????? ????????
    public function getCountDevices($data = null) {

        if ($data) {
            $query_part = "WHERE `name` = '$data' \n
                             OR `type` = '$data'\n
                             OR `status` = '$data'\n
                             OR `owner` = '$data'\n
                             OR `user` = '$data'\n
                             OR `adress` = '$data'\n
                     ";
        }
        //?????????? ??????
        $sql = (" SELECT count(id) as count
                  FROM `devices`
                  $query_part
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

}
