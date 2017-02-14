<?php

class Application_Model_DbTable_Update extends Zend_Db_Table_Abstract {

    //статистика количества аппаратов по имени
    public function makeBase() {
        
//        $sql = ("DROP DATABASE ss1");
//        $this->_db->query($sql);
// 
//        $sql = ("CREATE DATABASE ss1");
//        $this->_db->query($sql);
// 
//        $sql = ("use ss1");
//        $this->_db->query($sql);
//         
//        //создаем таблицу данных "data"
//        $sql = ("CREATE TABLE IF NOT EXISTS visitors (
//                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
//                  date timestamp,
//                  ips varchar(100) NOT NULL,
//                  visits varchar(100) NOT NULL
//                )");
//        $this->_db->query($sql);
//        //создаем таблицу данных "data"
//        $sql = ("CREATE TABLE IF NOT EXISTS access (
//                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
//                  username varchar(100) NOT NULL,
//                  password varchar(100) NOT NULL,
//                  role varchar(100) NOT NULL
//                )");
//        $this->_db->query($sql);
//        
//        
//        //формируем запрос
//        $sql = ("INSERT INTO access
//                 VALUES 
//                 (1,'admin',MD5('admin'),'admin');           
//                ");
//
//        $this->_db->query($sql);

        //формируем запрос
        //таблица разрешения доступа (пользователя) "allow"
        $sql = ("CREATE TABLE IF NOT EXISTS allow (
                  id	int	NOT NULL AUTO_INCREMENT PRIMARY KEY,	 
                  username	varchar(30) NOT NULL REFERENCES access (username) ON DELETE CASCADE ON UPDATE CASCADE,
	          doc_index	varchar(30) NOT NULL,
                  doc_file	varchar(30) NOT NULL,
                  doc_delete	varchar(30) NOT NULL,
                  cat_index	varchar(30) NOT NULL,
                  cat_add       varchar(30) NOT NULL,
	          cat_edit	varchar(30) NOT NULL,
                  cat_del       varchar(30) NOT NULL,
                  cat_exl       varchar(30) NOT NULL,
	          his_index	varchar(30) NOT NULL,
	          rep_index	varchar(30) NOT NULL,
	          stat_index	varchar(30) NOT NULL,
	          sal_index	varchar(30) NOT NULL,
	          sal_add       varchar(30) NOT NULL,
	          sal_edit	varchar(30) NOT NULL,
	          sal_delete	varchar(30) NOT NULL,
                  sal_toexcel	varchar(30) NOT NULL,
                  rps_index	varchar(30) NOT NULL,
                  rps_add       varchar(30) NOT NULL,	
                  rps_edit	varchar(30) NOT NULL,
                  rps_delete	varchar(30) NOT NULL,
                  rps_toexcel	varchar(30) NOT NULL,
                  rps_toexcelmounth	varchar(30) NOT NULL,
                  rps_statistic	varchar(30) NOT NULL,
                  war_index	varchar(30) NOT NULL,
                  war_add       varchar(30) NOT NULL,
                  war_edit	varchar(30) NOT NULL,
                  war_delete	varchar(30) NOT NULL,
                  war_toexcel	varchar(30) NOT NULL,
                  war_history	varchar(30) NOT NULL,
                  war_load	varchar(30) NOT NULL,
                  war_unload	varchar(30) NOT NULL,	
                  set_index	varchar(30) NOT NULL,	
                  set_names	varchar(30) NOT NULL,
                  set_addname	varchar(30) NOT NULL,	
                  set_editname	varchar(30) NOT NULL,
                  set_deletename	varchar(30) NOT NULL,	
                  set_types	varchar(30) NOT NULL,	
                  set_addtype	varchar(30) NOT NULL,
                  set_edittype	varchar(30) NOT NULL,	
                  set_deletetype	varchar(30) NOT NULL,	
                  set_owners	varchar(30) NOT NULL,	
                  set_addowner	varchar(30) NOT NULL,
                  set_editowner	varchar(30) NOT NULL,	
                  set_deleteowner	varchar(30) NOT NULL,	
                  set_users	varchar(30) NOT NULL,	
                  set_adduser	varchar(30) NOT NULL,	
                  set_edituser	varchar(30) NOT NULL,	
                  set_deleteuser	varchar(30) NOT NULL,	
                  set_status	varchar(30) NOT NULL,	
                  set_addstatus	varchar(30) NOT NULL,	
                  set_editstatus	varchar(30) NOT NULL,	
                  set_deletestatus	varchar(30) NOT NULL,	
                  set_access	varchar(30) NOT NULL,	
                  set_addaccess	varchar(30) NOT NULL,	
                  set_editaccess	varchar(30) NOT NULL,	
                  set_deleteaccess	varchar(30) NOT NULL,
                  set_prices	varchar(30) NOT NULL,	
                  set_addprices	varchar(30) NOT NULL,	
                  set_editprices	varchar(30) NOT NULL,
                  set_deleteprices	varchar(30) NOT NULL,
                  ser_index	varchar(30) NOT NULL,
                  ser_add	varchar(30) NOT NULL,
                  ser_edit	varchar(30) NOT NULL,
                  ser_delete	varchar(30) NOT NULL,	
                  ser_toexcel	varchar(30) NOT NULL,	
                  ser_invoice	varchar(30) NOT NULL
                )");
        $this->_db->query($sql);
    
        //создаем таблицу данных "data"
        $sql = ("CREATE TABLE IF NOT EXISTS data (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  description varchar(100) NOT NULL,
                  path varchar(100) NOT NULL
                )");
        $this->_db->query($sql);

        //создаем таблицу данных "name"
        $sql = ("CREATE TABLE IF NOT EXISTS name (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  name varchar(100) NOT NULL,
                  INDEX (name)
                )"
                );
        $this->_db->query($sql);

        //создаем таблицу данных "owner"
        $sql = ("CREATE TABLE IF NOT EXISTS owner (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  owner varchar(100) NOT NULL,
                  INDEX (owner)
                )");
        $this->_db->query($sql);
 
        //создаем таблицу данных "status"
        $sql = ("CREATE TABLE IF NOT EXISTS status (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  status varchar(100) NOT NULL,
                  INDEX (status)
                )");
        $this->_db->query($sql);
 
        //создаем таблицу данных "type"
        $sql = ("CREATE TABLE IF NOT EXISTS type (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  type varchar(100) NOT NULL,
                  INDEX (type)
                )");
        $this->_db->query($sql);
 
        //создаем таблицу данных "user"
        $sql = ("CREATE TABLE IF NOT EXISTS user (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  user varchar(100) NOT NULL,
                  INDEX (user)
                )");
        $this->_db->query($sql);
 
        //создаем таблицу данных "devices"
        $sql = ("CREATE TABLE IF NOT EXISTS devices (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  adress varchar(1000) NOT NULL,
                  number varchar(100) NOT NULL,
                  name varchar(100) NOT NULL,
                  owner varchar(100) NOT NULL,
                  user varchar(100) NOT NULL,
                  status varchar(100) NOT NULL,
                  type varchar(100) NOT NULL,
                  city varchar(100) NOT NULL,
                  tt_name varchar(100) NOT NULL,
                  tt_user varchar(100) NOT NULL,
                  tt_phone varchar(100) NOT NULL,
                  lat varchar(50) NOT NULL,
                  lng varchar(50) NOT NULL,
                  `show` varchar(20) NOT NULL DEFAULT 'NO',
                  color VARCHAR( 20 ) NOT NULL DEFAULT  'Red',
                  INDEX (number),
                  INDEX (name),
                  INDEX (owner),
                  INDEX (user),
                  INDEX (status),
                  INDEX (type),
                  FOREIGN KEY (name) REFERENCES name (name) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (owner) REFERENCES owner (owner) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (user) REFERENCES user (user) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (status) REFERENCES status (status) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (type) REFERENCES type (type) ON DELETE CASCADE ON UPDATE CASCADE
                )");

        $this->_db->query($sql);
 
        //создаем таблицу данных "repairs"
        $sql = ("CREATE TABLE IF NOT EXISTS repairs (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  date timestamp,
                  number varchar(100) NOT NULL,
                  claim varchar(100) NOT NULL,
                  diagnos varchar(100) NOT NULL,
                  diagnoswork varchar(100) NOT NULL,
                  spares text NOT NULL,
                  comments varchar(100) NOT NULL,
                  counter varchar(100) NOT NULL,
                  serialize_data text NOT NULL,
                  serialize_checked text NOT NULL,
                  INDEX (number),
                  FOREIGN KEY (number) REFERENCES devices (number) ON DELETE CASCADE ON UPDATE CASCADE
                )");

        $this->_db->query($sql);

        //создаем таблицу данных "warehouse"
        $sql = ("CREATE TABLE IF NOT EXISTS warehouse (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  serial varchar(100) NOT NULL,
                  name varchar(100) NOT NULL,
                  type varchar(100) NOT NULL,
                  remain varchar(100) NOT NULL,
                  lastadding timestamp,
                  price varchar(100) NOT NULL DEFAULT 0,
                  path varchar(100) NOT NULL,
                  INDEX (serial),
                  INDEX (name)
                )");

        $this->_db->query($sql);

        //создаем таблицу данных "warehistory"
        $sql = ("CREATE TABLE IF NOT EXISTS warehistory (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  serial varchar(100) NOT NULL,
                  name varchar(100) NOT NULL,
                  remain varchar(100) NOT NULL,
                  expence varchar(100) NULL DEFAULT 0,
                  presence varchar(100) NOT NULL,
                  method varchar(100) NOT NULL,
                  data timestamp,
                  INDEX (serial),
                  INDEX (name),
                  FOREIGN KEY (serial) REFERENCES warehouse (serial) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (name) REFERENCES warehouse (name) ON DELETE CASCADE ON UPDATE CASCADE
                )");

        $this->_db->query($sql);
        
        //создаем таблицу данных "history"
        $sql = ("CREATE TABLE IF NOT EXISTS history (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  data timestamp,
                  number varchar(100) NOT NULL,
                  owner varchar(100) NOT NULL,
                  user varchar(100) NOT NULL,
                  status varchar(100) NOT NULL,
                  adress varchar(1000) NOT NULL,
                  city varchar(100) NOT NULL,
                  tt_name varchar(100) NOT NULL,
                  tt_user varchar(100) NOT NULL,
                  tt_phone varchar(100) NOT NULL,
                  INDEX (number),
                  FOREIGN KEY (number) REFERENCES devices (number) ON DELETE CASCADE ON UPDATE CASCADE
                )");

        $this->_db->query($sql);
        
        //создаем таблицу данных "prices"
        $sql = ("CREATE TABLE IF NOT EXISTS prices (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  name varchar(100) NOT NULL,
                  price varchar(100) NOT NULL
                )");

        $this->_db->query($sql);
        
        //создаем таблицу данных "sales"
        $sql = ("CREATE TABLE IF NOT EXISTS sales (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  date timestamp,
                  number varchar(100) NOT NULL,
                  name varchar(100) NOT NULL,
                  buyer varchar(100) NOT NULL,
                  status varchar(100) NOT NULL,
                  INDEX (name),
                  INDEX (status),
                  FOREIGN KEY (name) REFERENCES name (name) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (status) REFERENCES status (status) ON DELETE CASCADE ON UPDATE CASCADE
                )");

        $this->_db->query($sql);
        
        //создаем таблицу данных "service"
        $sql = ("CREATE TABLE IF NOT EXISTS service (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  date timestamp,
                  number varchar(100) NOT NULL,
                  name varchar(100) NOT NULL,
                  client varchar(100) NOT NULL,
                  status varchar(100) NOT NULL,
                  claim varchar(100) NOT NULL,
                  diagnos varchar(100) NOT NULL,
                  work text NOT NULL,
                  spares text NOT NULL,
                  counter varchar(100) NOT NULL,
                  comments varchar(100) NOT NULL,
                  serialize_data text NOT NULL,
                  serialize_price text NOT NULL
                )");

        $this->_db->query($sql);
    }

    //статистика количества аппаратов по имени
    public function loadBase() {

        //формируем запрос
        $sql = ("INSERT INTO name (name)
                 VALUES 
                 ('Trevi Chiara'),           
                 ('Viena Superavtomatika'),           
                 ('Royal Professional'),           
                 ('Aulika Focus'),           
                 ('Aulika Top'),           
                 ('Bianchi'),           
                 ('Cafe Crema'),           
                 ('Cafe Grande'),           
                 ('Cafe Gusto'),           
                 ('Cafe Nova'),           
                 ('Cafe Prima'),           
                 ('Fedra Saeco'),           
                 ('Incanto'),           
                 ('Royal De Luxe'),           
                 ('Royal Office'),           
                 ('Spinel Pinocchio'),           
                 ('Viena Edition'),           
                 ('Villa Spidem');      
                ");

        $this->_db->query($sql);

        //формируем запрос
        $sql = ("INSERT INTO type (type)
                 VALUES 
                 ('Aulika'),           
                 ('Bianchi'),           
                 ('Classic'),           
                 ('Fedra'),           
                 ('Incanto'),           
                 ('Royal'),           
                 ('Spinel'),           
                 ('Superavtomat'),           
                 ('Viena'),           
                 ('Villa'),           
                 ('WMF');      
                ");

        $this->_db->query($sql);

        //формируем запрос
        $sql = ("INSERT INTO owner (owner)
                 VALUES 
                 ('ТОВ КофеТрейд'),           
                 ('ТОВ КофеГруп');           
                ");

        $this->_db->query($sql);

        //формируем запрос
        $sql = ("INSERT INTO user (user)
                 VALUES 
                 ('Менеджер 1'),           
                 ('Менеджер 2'),           
                 ('Менеджер 3'),           
                 ('Менеджер 4');           
                ");

        $this->_db->query($sql);

        //формируем запрос
        $sql = ("INSERT INTO status (status)
                 VALUES 
                 ('В аренде'),           
                 ('В ремонте'),           
                 ('В работе'),           
                 ('Разборка');           
                ");

        $this->_db->query($sql);
        
        //формируем запрос
        $sql = ("INSERT INTO devices
                 SET    
                 id       = 1,
                 number   = 'APN-123',
                 name     = 'Trevi Chiara',
                 type     = 'Viena',
                 owner    = 'ТОВ КофеТрейд',
                 user     = 'Менеджер 1',
                 status   = 'В работе',
                 city     = 'Киев',
                 adress   = 'Крещатик 1а',
                 tt_name  = 'Магазин Фора',
                 tt_user  = 'Иванов Иван',
                 tt_phone = '(050)123-45-67',
                 lat      = 'нет данных',
                 lng      = 'нет данных';

                ");

        $this->_db->query($sql);

        //формируем запрос
        $sql = ("INSERT INTO devices
                 SET    
                 id       = 2,
                 number   = 'APN-456',
                 name     = 'Viena Superavtomatika',
                 type     = 'Viena',
                 owner    = 'ТОВ КофеТрейд',
                 user     = 'Менеджер 2',
                 status   = 'В ремонте',
                 city     = 'Киев',
                 adress   = 'Воскресенская 14б',
                 tt_name  = 'Магазин Новус',
                 tt_user  = 'Иванов Тарас',
                 tt_phone = '(050)123-45-67',
                 lat      = 'нет данных',
                 lng      = 'нет данных';

                ");

        $this->_db->query($sql);

        //формируем запрос
        $sql = ("INSERT INTO devices
                 SET    
                 id       = 3,
                 number   = 'APN-789',
                 name     = 'Royal Professional',
                 type     = 'Royal',
                 owner    = 'ТОВ КофеТрейд',
                 user     = 'Менеджер 3',
                 status   = 'Разборка',
                 city     = 'Киев',
                 adress   = 'Милютенко 17а',
                 tt_name  = 'Магазин Катруся',
                 tt_user  = 'Иванов Кирил',
                 tt_phone = '(050)123-45-67',
                 lat      = 'нет данных',
                 lng      = 'нет данных';

                ");

        $this->_db->query($sql);

    }

    //статистика количества аппаратов по имени
    public function updateBase() {

        //формируем запрос
        $sql = ("ALTER TABLE new 
                 ADD COLUMN it2 int(11) NOT NULL AUTO_INCREMENT,
                 ADD PRIMARY KEY (it2),
                 ADD COLUMN test2 varchar(200) NOT NULL DEFAULT 'gtht'

                ");

        $this->_db->query($sql);
    }

}
