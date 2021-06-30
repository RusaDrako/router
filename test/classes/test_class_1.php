<?php
namespace test\classes;

/**
 *
 */
class test_class_1 {
	function method_0() {
		return 'Test ' . get_called_class() . '->' . __FUNCTION__ . " ()";
	}
	function method_1($val) {
		return 'Test ' . get_called_class() . '->' . __FUNCTION__ . " (val={$val})";
	}
	function method_2($val, $val2) {
		return 'Test ' . get_called_class() . '->' . __FUNCTION__ . " (val={$val};val2={$val2})";
	}
	function method_3($val, $val2, $val3) {
		return 'Test ' . get_called_class() . '->' . __FUNCTION__ . " (val={$val};val2={$val2};val3={$val3})";
	}
	function method_default() {
		return 'Test ' . get_called_class() . '->' . __FUNCTION__;
	}
}
