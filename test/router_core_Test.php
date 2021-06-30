<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/autoload.php');
//require_once(__DIR__ . '/mock/test_item.php');
//require_once(__DIR__ . '/mock/test_data.php');
//require_once(__DIR__ . '/mock/stub_db.php');





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 */
class router_core_Test extends TestCase {
	/** Имя тестируемого объекта */
	private $class_name_router = 'RusaDrako\\router\\router_core';
	/** Тестируемый объект */
	private $_test_object = null;



	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {}



	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}



	/** Создаёт объект маршрутизатора */
	protected function createObject($type, $route) {
		$class_name = $this->class_name_router;
		# Объект
		$this->_test_object = new $class_name();
		# Тип REST
		$this->_test_object->set_type_rest($type);
		# Текущий маршрут
		$this->_test_object->set_route($route);
		# Корневая директория
		$this->_test_object->set_root_folder(__DIR__ . '/../');
	}










	/**- Проверяет функцию нормализации имени маршрута * /
	public function test_normalization_route_name() {
		$this->createObject('GET', '/test/');

		$result = $this->_test_object->normalization_route_name('');
		$this->assertEquals($result, '/');
		$result = $this->_test_object->normalization_route_name('/');
		$this->assertEquals($result, '/');
		$result = $this->_test_object->normalization_route_name('/test/');
		$this->assertEquals($result, '/test/');
		$result = $this->_test_object->normalization_route_name('/test');
		$this->assertEquals($result, '/test/');
		$result = $this->_test_object->normalization_route_name('test/');
		$this->assertEquals($result, '/test/');
		$result = $this->_test_object->normalization_route_name('test');
		$this->assertEquals($result, '/test/');
	}



	/** Проверяет метод возврата группы */
	public function test_get_group() {
		$this->createObject('GET', '/test/111/{}/333/');
		$result = $this->_test_object->get_group();
		$this->assertEquals($result, 'test');
		$result = $this->_test_object->get_group(2);
		$this->assertEquals($result, '111');
		$result = $this->_test_object->get_group(3);
		$this->assertEquals($result, '{}');
		$result = $this->_test_object->get_group(4);
		$this->assertEquals($result, '333');
		$result = $this->_test_object->get_group(5);
		$this->assertEquals($result, '');
	}










	/** Проверяет функцию */
	public function test_router_func() {
		$this->createObject('GET', '/test/');

		$func = function () {
			return 'Test 1';
		};
		$this->_test_object->add_router('GET', '/test/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 1');
	}



	/** Проверяет метод */
	public function test_router_class_method() {
		$this->createObject('GET', '/test/');

		$this->_test_object->add_router('GET', '/test/', 'test\classes\test_class_1@method_0' );

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test test\classes\test_class_1->method_0 ()');
	}










	/** Проверяет вызов метода get */
	public function test_router_method_get() {
		$this->createObject('GET', '/test/');

		$func = function () {
			return 'Test 111';
		};
		$this->_test_object->add_router('GET', '/test/', $func);
		$func = function () {
			return 'Test 222';
		};
		$this->_test_object->add_router('POST', '/test/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 111');
	}



	/** Проверяет вызов метода post */
	public function test_router_method_post() {
		$this->createObject('POST', '/test/');

		$func = function () {
			return 'Test 111';
		};
		$this->_test_object->add_router('GET', '/test/', $func);
		$func = function () {
			return 'Test 222';
		};
		$this->_test_object->add_router('POST', '/test/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 222');
	}










	/** Проверяет функцию с аргументом */
	public function test_router_func_arg_1() {
		$this->createObject('GET', '/test/111/');

		$func = function ($val) {
			return "Test {$val}";
		};
		$this->_test_object->add_router('GET', '/test/{}/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 111');
	}



	/** Проверяет метод с аргументом */
	public function test_router_method_arg_1() {
		$this->createObject('GET', '/test/111/');

		$this->_test_object->add_router('GET', '/test/{}/', 'test\classes\test_class_1@method_1');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test test\classes\test_class_1->method_1 (val=111)');
	}



	/** Проверяет функцию с 2 именованными аргументами */
	public function test_router_func_arg_2() {
		$this->createObject('GET', '/test/111/222/');

		$func = function ($val, $val2) {
			return "Test {$val} {$val2}";
		};
		$this->_test_object->add_router('GET', '/test/{val2}/{val}/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 222 111');
	}



	/** Проверяет метод с 2 именованными аргументами */
	public function test_router_method_arg_2() {
		$this->createObject('GET', '/test/111/222/');

		$this->_test_object->add_router('GET', '/test/{val2}/{val}/', 'test\classes\test_class_1@method_2');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test test\classes\test_class_1->method_2 (val=222;val2=111)');
	}



	/** Проверяет функцию с одним именованными аргументом и двумя не именованными */
	public function test_router_func_arg_3() {
		$this->createObject('GET', '/test/111/222/333/');

		$func = function ($val, $val2, $val3) {
			return "Test {$val} {$val2} {$val3}";
		};
		$this->_test_object->add_router('GET', '/test/{val2}/{}/{}/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 222 111 333');
	}



	/** Проверяет метод с 2 именованными аргументами */
	public function test_router_method_arg_3() {
		$this->createObject('GET', '/test/111/222/333/');

		$this->_test_object->add_router('GET', '/test/{val2}/{}/{}/', 'test\classes\test_class_1@method_3');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test test\classes\test_class_1->method_3 (val=222;val2=111;val3=333)');
	}










	/** Проверяет ошибки на пустое действие */
	public function test_router_error_empty() {
		$this->createObject('GET', '/');

		$this->_test_object->add_router('GET', '/', '');

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('/: Нет связанного действия');
		$result = $this->_test_object->router();
	}



	/** Проверяет ошибки на пустое действие */
	public function test_router_error_null() {
		$this->createObject('GET', '/');

		$this->_test_object->add_router('GET', '/', null);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('/: Нет связанного действия');
		$result = $this->_test_object->router();
	}



	/** Проверяет ошибки на пустое действие */
	public function test_router_error_false() {
		$this->createObject('GET', '/');

		$this->_test_object->add_router('GET', '/', false);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('/: Нет связанного действия');
		$result = $this->_test_object->router();
	}



	/** Проверяет ошибки отсутствующий файл класса */
	public function test_router_error_file_class() {
		$this->createObject('GET', '/');

		$this->_test_object->add_router('GET', '/', 'test\classes\test_class_2@method_0');

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('/: Отсутствует файл класса test/classes/test_class_2.php');
		$result = $this->_test_object->router();
	}



	/** Проверяет ошибки отсутствующий класс */
	public function test_router_error_class() {
		$this->createObject('GET', '/');

		$this->_test_object->add_router('GET', '/', 'test\classes\test_class_null@method_0');

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('/: Отсутствует класс \test\classes\test_class_null');
		$result = $this->_test_object->router();
	}



	/** Проверяет ошибки отсутствующий метод */
	public function test_router_error_method() {
		$this->createObject('GET', '/');

		$this->_test_object->add_router('GET', '/', 'test\classes\test_class_1@method_null');

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('/: Отсутствует метод класса \test\classes\test_class_1 ---> method_null');
		$result = $this->_test_object->router();
	}










	/** Проверяет вывод страницы по умолчанию - сообщение, когда страница не указана */
	public function test_router_default_empty() {
		$this->createObject('GET', '/test_def/');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Нет связанного действия');
	}



	/** Проверяет вывод страницы по умолчанию - функция */
	public function test_router_default_func() {
		$this->createObject('GET', '/test_def/');

		$func = function () {
			return 'default';
		};
		$this->_test_object->add_default($func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'default');
	}



	/** Проверяет вывод страницы по умолчанию - метод */
	public function test_router_default_method() {
		$this->createObject('GET', '/test_def/');

		$this->_test_object->add_default('test\classes\test_class_1@method_default');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test test\classes\test_class_1->method_default');
	}



	/** Проверяет вывод страницы по умолчанию - функция для типа get */
	public function test_router_default_method_get() {
		$this->createObject('GET', '/test_def/');

		$func = function () {
			return 'default';
		};
		$this->_test_object->add_default($func, 'GET');
		$this->_test_object->add_default('test\classes\test_class_1@method_default', 'POST');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'default');
	}



	/** Проверяет вывод страницы по умолчанию - метод для типа post */
	public function test_router_default_method_post() {
		$this->createObject('POST', '/test_def/');

		$func = function () {
			return 'default';
		};
		$this->_test_object->add_default($func, 'GET');
		$this->_test_object->add_default('test\classes\test_class_1@method_default', 'POST');

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test test\classes\test_class_1->method_default');
	}





/**/
}
