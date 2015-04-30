<?php

class Zend_Controller_Action_Helper_Navigation extends
Zend_Controller_Action_Helper_Abstract {

    private $sort;
    private $select_limit;
    private $page;
    private $allpages;
    private $count;
 
    /**
     * @var \Zend_View 
     */
    private $view;

    public function setView(\Zend_View $view) {
        $this->view = $view;
    }
    
    
    //передача параметров во $view
    public function postDispatch()
    {
        $this->view->previos_page = $this->previospage(); 
        $this->view->next_page = $this->nextpage(); 
        $this->view->last_page = $this->lastpage(); 
        $this->view->button_parameters = $this->buttonparameters();
        $this->view->page_first = $this->pagefirst();
        $this->view->page_last = $this->pagelast();
        $this->view->count = $this->count();
    }

    public function initNav($sort, $select_limit, $page, $allpages) {
        $this->sort = $sort;
        $this->select_limit = $select_limit;
        $this->page = $page;
        $this->count = count($allpages);

        if ($this->sort != '') {
            $_SESSION['sort'] = $this->sort;
        } else {
            if (!isset($_SESSION['sort'])) {
                $_SESSION['sort'] = 'id DESC';
            }
        }

        if ($this->select_limit != '') {
            $_SESSION['select_limit'] = $this->select_limit;
        } else {
            if (!isset($_SESSION['select_limit'])) {
                $_SESSION['select_limit'] = 10;
            }
        }

        if ($this->page) {
            $_SESSION['page'] = $this->page;
        } else {
            if (!isset($_SESSION['page'])) {
                $_SESSION['page'] = 1;
            }
        }

        $allpages = $this->count / $_SESSION['select_limit'];
        
        if ($allpages - intval($allpages) > 0) {
            $allpages = intval($allpages) + 1;
        }

        $this->allpages = $allpages;

//        print_r('sort=');
//        print_r($this->sort . '<br>');
//        print_r('select_limit=');
//        print_r($this->select_limit . '<br>');
//        print_r('page=');
//        print_r($this->page . '<br>');
//        print_r('allpages=');
//        print_r($this->allpages . '<br>');
    }

    //функция параметров сортировки
    public function sortby() {

        return $_SESSION['sort'];
    }

    //функция параметров количества строк
    public function selectlimit() {

        return $_SESSION['select_limit'];
    }

    //функция определения текущей страницы
    public function page() {

        return (int) $_SESSION['page'];
    }

    //функция кнопки 'назад' : активна, не активна
    public function previospage() {

        //настройка кнопок назад/вперед
        if ($_SESSION['page'] > 1) {
            $previos_page = '';
        } else {
            $previos_page = 'disabled';
        }

        return $previos_page;
    }

    //функция кнопки 'вперед' : активна, не активна
    public function nextpage() {

        if ($_SESSION['page'] < $this->allpages) {
            $next_page = '';
        } else {
            $next_page = 'disabled';
        }

        return $next_page;
    }

    //функция параметров количества строк
    public function lastpage() {

        //настройка кнопок назад/вперед
        if ($_SESSION['page'] < $this->allpages) {
            $last_page = $_SESSION['page'] + 1;
        } else {
            $last_page = $_SESSION['page'];
        }

        return $last_page;
    }

    //функция параметров количества строк
    public function pagefirst() {

        return $_SESSION['page'] * $_SESSION['select_limit'] - $_SESSION['select_limit'] + 1;
    }

    //функция параметров количества строк
    public function pagelast() {
        if ($_SESSION['page'] * $_SESSION['select_limit'] <= $this->count) {
            return $_SESSION['page'] * $_SESSION['select_limit'];
        } else {
            return $this->count;
        }
    }

    //функция параметров количества строк
    public function count() {

        return $this->count;
    }

    //функция параметров количества строк
    public function buttonparameters() {

        $button_parameters = array(
            '1' => array(
                'active' => ''
                , 'number' => '1'
            ),
            '2' => array(
                'active' => ''
                , 'number' => '2'
            ),
            '3' => array(
                'active' => ''
                , 'number' => '3'
            ),
            '4' => array(
                'active' => ''
                , 'number' => '4'
            ),
            '5' => array(
                'active' => ''
                , 'number' => '5'
            ),
            '6' => array(
                'active' => ''
                , 'number' => '6'
            )
        );

        //выделение не активных кнопок
        for ($i = 6; $i > $this->allpages; $i--) {
            $button_parameters[$i]['active'] = 'disabled';
        }

        $page = $_SESSION['page'];
        //выделение активной кнопки
        if ($page < 5) {
            $button_parameters[$page]['active'] = 'active';
        } else {

            $button_parameters['1']['number'] = $page - 4;
            $button_parameters['2']['number'] = $page - 3;
            $button_parameters['3']['number'] = $page - 2;
            $button_parameters['4']['number'] = $page - 1;
            $button_parameters['5']['number'] = $page;
            $button_parameters['5']['active'] = 'active';
            $button_parameters['6']['number'] = $page + 1;

            if ($button_parameters['6']['number'] > $this->allpages) {
                $button_parameters['6']['active'] = 'disabled';
            }
        }

        return $button_parameters;
    }

}