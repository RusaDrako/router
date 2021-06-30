<?php

namespace RusaDrako\router;





/** <b>router</b><br> Модуль обработки маршрутов.
 * @author Petukhov Leonid
 */
class router_add extends router_core {



	/** Задаём текущий маршрут по настройкам сервера */
	public function set_server_setting() {
		$this->set_root_folder($_SERVER['DOCUMENT_ROOT']);
		$this->set_type_rest($_SERVER['REQUEST_METHOD']);
		if (isset($_SERVER['REQUEST_URI'])) {
			$val_route = explode('?', $_SERVER['REQUEST_URI'])[0];
			if ($val_route[-1] != '/' ) {
				if (!isset($_SERVER['REDIRECT_URL'])) {
					$val_route .= '/';
				} else {
					$val_route = $_SERVER['REDIRECT_URL'] . '/';
				}
			}
			$this->arr_router = \explode('/', $val_route);
		} else {
			$this->arr_router = ['',''];
			$val_route = '/';
		}
		$this->set_route($val_route);
	}





	/** Задаёт маршруты для всех типов REST
	 * @param string $route_mask Маршрут (маска)
	 * @param mixed $action Связанное действие
	 */
	public function any($route_mask, $action = null) {
		# Проходим по массиву типов запросов
		foreach($this->arr_router_settings as $k => $v) {
			# Заносим обработчик маршрута
			$this->add_router($k, $route_mask, $action);
		}
	}





	/** Задаёт маршруты для GET
	 * @param string $route_mask Маршрут (маска)
	 * @param mixed $action Связанное действие
	 */
	public function get($route_mask, $action = null) {
		# Заносим обработчик маршрута
		$this->add_router('GET', $route_mask, $action);
	}





	/** Задаёт маршруты для POST
	 * @param string $route_mask Маршрут (маска)
	 * @param mixed $action Связанное действие
	 */
	public function post($route_mask, $action = null) {
		# Заносим обработчик маршрута
		$this->add_router('POST', $route_mask, $action);
	}



/**/
}
