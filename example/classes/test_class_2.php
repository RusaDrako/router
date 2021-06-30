<?php
namespace example\classes;

/**
 *
 */
class test_class_2 {
	function __construct() {
		echo 'Вызван класс: ' . get_called_class() . '<br>';
	}
	function method_1($val) {
		echo 'Вызван метод: ' . __FUNCTION__ . '<br>';
		echo '$val: ' . $val . '<br>';
	}
	function method_2($val, $val_2) {
		echo 'Вызван метод: ' . __FUNCTION__ . '<br>';
		echo '$val: ' . $val . '<br>';
		echo '$val_2: ' . $val_2 . '<br>';
	}
}
