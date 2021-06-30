<?php

namespace RusaDrako\router;




/** Работа с переменными */
trait trait__arg {
	/** Массив заданных регулярных выражений для имён переменных */
	private $_arr_arg			= [];
	/** Регулярное выражение по умолчанию */
	private $_arg_def			= '[a-z0-9_-]+';



	/** Добавляет регулярное выражение для имени переменной */
	public function arg_add ($name, $preg) {
		$this->_arr_arg[$name] = $preg;
		return $this;
	}



/**/
}
