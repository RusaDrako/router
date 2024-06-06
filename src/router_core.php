<?php

namespace RusaDrako\router;



/** Класс обработки маршрутов.
 * @author Petukhov Leonid
 */
class router_core {
	use trait__arg;
	/** Массив с элементами текущего маршрута */
	protected $arr_router            = [];
	/** Текущий маршрут */
	protected $_route                = false;
	/** Массив маршрутов по типам запросов */
	protected $arr_router_settings   = [
		'GET'       => [],
		'HEAD'      => [],
		'POST'      => [],
		'PUT'       => [],
		'PATCH'     => [],
		'DELETE'    => [],
		'OPTIONS'   => [],
/*		'API'       => [],*/
	];
	/** Маршрут по умолчанию */
	protected $router_default        = [];
	/** Метод запроса */
	protected $method                = false;
	/** Корневая папка */
	protected $root_folder           = false;

	/** Объект модели */
	protected static $_object        = null;






	/** Загрузка класса */
	function __construct () {
		$this->router_default   = $this->arr_router_settings;
		$this->add_default(false);
	}





	/** Выгрузка класса */
	public function __destruct () {}




	/** */
	public function __debugInfo() {
		return [
			'arg_name'        => $this->_arr_arg,
			'routes'          => $this->arr_router_settings,
			'route_default'   => $this->router_default,
		];
	}



	/** Вызов объекта класса
	* @return object Объект модели
	*/
	public static function call() {
		if (null === self::$_object) {
			self::$_object = new static();
		}
		return self::$_object;
	}





	/** Нормализует имя маршрута */
	private function _normalization_route_name($value) {
		if ('/' != $value[0])    {$value = "/{$value}";}
		if ('/' != $value[-1])   {$value .= '/';}
		return $value;
	}





	/** Задаёт корневую папку классов
	 * @param string $value Корневая папка для поиска классов
	 */
	public function set_root_folder(string $value) {
		if (\strlen($value) && '/' != $value[-1]) {$value .= '/';}
		$this->root_folder   = $value;
	}





	/** Задаёт текущий маршрут
	 * @param string $value Текущий маршрут для обработки
	 */
	public function set_route(string $value) {
		# Нормализуем имя маршрута
		$value              = $this->_normalization_route_name($value);
		$this->arr_router   = \explode('/', $value);
		$this->_route       = $value;
	}





	/** Задаёт текущий тип REST
	 * @param string $value Текущий тип REST
	 */
	public function set_type_rest(string $value) {
		$this->method   = \strtoupper($value);
	}





	/** Добавляет маршрут
	 * @param string $type Тип REST
	 * @param string $route_mask Маршрут (маска)
	 * @param mixed $action Связанное действие
	 */
	public function add_router(string $type, string $route_mask, $action) {
		# Нормализуем имя маршрута
		$route_mask   = $this->_normalization_route_name($route_mask);
		$type         = \strtoupper($type);
		# Если тип запроса существует
		if (key_exists($type, $this->arr_router_settings)) {
			# Заносим обработчик маршрута
			$this->arr_router_settings[$type][$route_mask]   = $action;
		}
	}





	/** Выводит наименование уровня маршрута
	 * @param int $num Номер уровня
	 */
	public function get_group(int $num = 1) {
		if (key_exists($num, $this->arr_router)) {
			return $this->arr_router[$num];
		} else {
			return '';
		}
	}





	/** Обрабатывает текущий маршрут */
	public function router() {
		# Вывод информации по маршрутам
		$argv   = [];
		# Проходим по всем маршрутам метода
		foreach ($this->arr_router_settings[$this->method] as $k => $v) {
			# Формирует регулярное выражение
			$reg_data      = $this->_create_reg($k);
			# Регулярное выражение
			$reg           = $reg_data[0];
			# Массив ключей аргументов
			$arr_reg_key   = $reg_data[1];
			if (preg_match($reg, $this->_route)) {
				preg_match_all($reg, $this->_route, $arg);
				# Удаляем первый элемент, т.к. это строка адреса
				\array_shift($arg);
				foreach($arg as $k_2 => $v_2) {
					if ($arr_reg_key[$k_2]) {
						$argv[$arr_reg_key[$k_2]]   = $v_2[0];
					} else {
						$argv[]   = $v_2[0];
					}
				}
				try {
					# Выполняем действие
					$content   = $this->_router_output($v, $argv);
					# Возвращаем результат
					return $content;
				# Маршрут возвращает ошибку
				} catch (\Exception $e) {
					# Генерируем ошибку
					throw new \Exception($k . ': ' . $e->getMessage(), 1);
					exit;
				}
			}
		}
		# Вывод страницы по умолчанию
		try {
			return $this->_router_output($this->router_default[$this->method]);
		# Самое последнее сообщение
		} catch (\Exception $e) {
			return $e->getMessage();
		}/**/
	}





	/** Обработка переменной/функции маршрута */
	protected function _router_output($value, $argv = []) {
		# Если переменная пустая
		if (!$value) {
			# Возвращаем пустое значение
			throw new \Exception('Нет связанного действия', 1);
			exit;
		# Если передана строка -> функция или метод
		} elseif (\is_string($value)) {
			# Разбиваем строку на массив
			$_arr_r           = explode('@', $value);
			$_arr_r[0]        = \str_replace('\\', '/', $_arr_r[0]);
			# Формируем имя файла
			$file_name        = "{$_arr_r[0]}.php";
			$full_file_name   = "{$this->root_folder}{$file_name}";
			# Формируем имя класса
			$class_name       = '\\' . \str_replace('/', '\\', $_arr_r[0]);
			# Если класс отсутстует - пытаемся загрузить
			if (!\class_exists($class_name)) {
				# Если файл отсутстует
				if (!\file_exists($full_file_name)) {
					# Возвращаем сообщение
					throw new \Exception('Отсутствует файл класса ' . $file_name, 1);
					exit;
				}
				# Подгружаем файл класса
				include_once ($full_file_name);
			}
			# Если класс отсутстует
			if (!\class_exists($class_name)) {
				# Возвращаем сообщение
				throw new \Exception('Отсутствует класс ' . $class_name, 1);
				exit;
			}
			# Формируем имя метода
			$method_name   = $_arr_r[1];
			# Если метод отсутстует
			if (!\method_exists($class_name , $method_name)) {
				# Возвращаем сообщение
				throw new \Exception('Отсутствует метод класса ' . $class_name . ' ---> ' . $method_name, 1);
				exit;
			}
			# Начинаем обработку функции
			$method       = new \ReflectionMethod($class_name, $method_name);
			# Формируем список аргументов функции
			$argv         = $this->_router_arg($argv, $method);
			# Создаём объект
			$obj_object   = new $class_name();
			# Выполняем метод из контрольного маршрута с переданными аргументами
			$content      = $method->invokeArgs($obj_object, $argv);
			return $content;
		# Обработка переданной функции
		} else {
			# Начинаем обработку функции
			$function   = new \ReflectionFunction($value);
			# Формируем список аргументов функции
			$argv       = $this->_router_arg($argv, $function);
			# Выполняем функцию из контрольного маршрута с переданными аргументами
			$content    = $function->invokeArgs($argv);
			return $content;
			# Возвращаем true
		}
		# Возвращаем false
		throw new \Exception(null, 1);
		exit;
	}





	/** Обработка переменной/функции маршрута */
	protected function _router_arg($arr_arg, $obj_method) {
		# Получаем список аргументов объекта (функции/метода)
		$params   = $obj_method->getParameters();
		$_argv    = [];
		# Проходим по всем аргументам
		foreach($params as $k => $v) {
			# Получаем имя параметра
			$param_name   = $v->getName();
			# Проверяем, имеетли он значение по умолчанию
			if ($v->isOptional()) {
				# Записываем значение
				$_argv[$param_name]   = $v->getDefaultValue();
			} else {
				# Записываем null
				$_argv[$param_name]   = null;
			}
		}
		# Проводим ряд с массивами, что бы присвоить значения соответствующим аргументам
		# Сводный массив переменных и аргументов
		$merge         = \array_merge($_argv, $arr_arg);
		# Временный результат с пустыми аргументами
		$_result       = \array_intersect_key($merge, $_argv);
		# Значения неприсвоенные аргументам
		$dif_1         = \array_diff_key($merge, $_argv);
		# Пустые аргументы функции
		$dif_control   = \array_diff_key($_argv, $arr_arg);
		# Заполняем пустые аргументы
		foreach($dif_control as $k => $v) {
			$val   = array_shift($dif_1);
			if (null !== $val) {
				$_result[$k]   = $val;
			}
		};
		return $_result;
	}





	/** Задаёт страницу по умолчанию
	 * @param mixed $action Связанное действие
	 * @param string $type Тип REST
	 */
	public function add_default($action, string $type = '') {
		# Переводим все буквы в верхний регистр
		$type   = \strtoupper($type);
		# Если существует указанный метод
		if (\key_exists($type, $this->arr_router_settings)) {
			# Заносим переменную в указанный тип
			$this->router_default[$type]   = $action;
			# Возвращаем объект
			return $this;
		}
		# Проходим по всем методам
		foreach($this->arr_router_settings as $k => $v) {
			# Заносим переменную в указанный тип
			$this->router_default[$k]   = $action;
		}
		# Возвращаем объект
		return $this;
	}





	/** Формирует регулярное выражение */
	protected function _create_reg($mask) {
		# Получаем маску маршрута
		$arr_key    = [];
		# Разбиваем на массив по '/'
		$arr_mask   = \explode('/', $mask);
		# Удаляем все пустые элементы
		$arr_mask   = \array_diff($arr_mask, [null, false, '']);
		# Проходим по элементам
		foreach ($arr_mask as $k => $v) {
			# Проверяем наличие переменной по наличию фигурных скобок
			if ('{' == $v[0]
					&& '}' == $v[-1]) {
				# Получаем ключ/имя переменной
				$key         = \substr($v, 1, strlen($v)-2);
				# Заносим ключ в массив
				$arr_key[]   = $key;
				# Проверяем наличие регулярного выражения для имени переменной
				if (\array_key_exists($key, $this->_arr_arg)) {
					# Записываем заданное регулярное
					$arr_mask[$k]   = "({$this->_arr_arg[$key]})";
				} else {
					# Записываем регулярное выражение по умолчанию
					$arr_mask[$k]   = "({$this->_arg_def})";
				}
			}
		}
		if (count($arr_mask)) {
			# Формируем отвер функции (Регулярное выражение + массив имён переменных)
			$result   = [
				'/^\/' . implode('\/', $arr_mask) . '\/$/iu',
				$arr_key,
			];
		} else {
			# Формируем отвер функции (Регулярное выражение + массив имён переменных)
			$result   = [
				'/^\/$/iu',
				$arr_key,
			];
		}
		# Возвращаем объект
		return $result;
	}





/**/
}
