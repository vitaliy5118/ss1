<?php

class Application_Model_Decoder {
    //put your code here
    public static function translit($s) {
        $s = (string) $s; // ����������� � ��������� ��������
        $s = strip_tags($s); // ������� HTML-����
        $s = str_replace(array("\n", "\r"), " ", $s); // ������� ������� �������
        $s = preg_replace("/\s+/", ' ', $s); // ������� ����������� �������
        $s = trim($s); // ������� ������� � ������ � ����� ������
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // ��������� ������ � ������ ������� (������ ���� ������ ������)
        $s = strtr($s, array('�' => 'a', '�' => 'b', '�' => 'v', '�' => 'g', '�' => 'd', '�' => 'e', '�' => 'e', '�' => 'j', '�' => 'z', '�' => 'i', '�' => 'y', '�' => 'k', '�' => 'l', '�' => 'm', '�' => 'n', '�' => 'o', '�' => 'p', '�' => 'r', '�' => 's', '�' => 't', '�' => 'u', '�' => 'f', '�' => 'h', '�' => 'c', '�' => 'ch', '�' => 'sh', '�' => 'shch', '�' => 'y', '�' => 'e', '�' => 'yu', '�' => 'ya', '�' => '', '�' => ''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // ������� ������ �� ������������ ��������
        $s = str_replace(" ", "-", $s); // �������� ������� ������ �����
        return $s; // ���������� ���������
    }
}