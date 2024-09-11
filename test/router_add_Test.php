<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/autoload.php');





/**
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class router_add_Test extends TestCase {
	/** Имя тестируемого объекта */
	private $class_name_router = 'RusaDrako\\router\\router_add';
	/** Тестируемый объект */
	private $_test_object = null;



	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {}



	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}



	/** Создаёт объект маршрутизатора */
	protected function createObject($type, $route) {
		$_SERVER['REQUEST_URI'] = $route;
		$_SERVER['REQUEST_METHOD'] = $type;
		$class_name = $this->class_name_router;
		$this->_test_object = new $class_name();
		$this->_test_object->set_server_setting();
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
		$this->_test_object->get('/test/', $func);
		$func = function () {
			return 'Test 222';
		};
		$this->_test_object->post('/test/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 222');
	}



	/** Проверяет вызов метода any */
	public function test_router_method_any_1() {
		$this->createObject('GET', '/test/');

		$func = function () {
			return 'Test 111';
		};
		$this->_test_object->any('/test/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 111');
	}



	/** Проверяет вызов метода any */
	public function test_router_method_any_2() {
		$this->createObject('POST', '/test/');

		$func = function () {
			return 'Test 111';
		};
		$this->_test_object->any('/test/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 111');
	}



	/** Проверяет маршрут с дополнительными параметрами */
	public function test_router_func_add_arg() {
		$this->createObject('GET', '/test/?ddd=123');

		$func = function () {
			return "Test 111";
		};
		$this->_test_object->get('/test/?ddd=123', $func);
		$func = function () {
			return "Test 000";
		};
		$this->_test_object->get('/test/', $func);
		$func = function () {
			return "Test 222";
		};
		$this->_test_object->get('/test/{}/', $func);

		$result = $this->_test_object->router();
		$this->assertEquals($result, 'Test 000');
	}



/**/
}
