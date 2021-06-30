<pre><?php
require_once('../src/autoload.php');
//require_once('autoload.php');

# Настройки подключения
$router = new RD_Router();

$router->set_server_setting();
$router->set_root_folder(__DIR__ . '/../');

$link = '/router/example/';
$func = function () use ($link) {
	echo "<a href=\"{$link}\"'>Старт</a><br>";
	echo "<a href=\"{$link}123/\"'>Загрузка встроеного в линк свойста</a><br>";
	echo "<a href=\"{$link}123/test/\"'>Загрузка встроеного в линк свойста (именованного)</a><br>";
	echo "<a href=\"{$link}сlass_1/\"'>Класс_1</a><br>";
	echo "<a href=\"{$link}сlass_1/test_1/\"'>Класс_2 cо свойством</a><br>";
	echo "<a href=\"{$link}сlass_1/test_1/test_2/\"'>Класс_3 cо свойством (именованным)</a><br>";
	echo "<a href=\"{$link}сlass_2/\"'>Ошибка (Отсутствует метод класса)</a><br>";
	echo "<a href=\"{$link}сlass_3/\"'>Ошибка (Нет связанного действия)</a><br>";
};
$router->any('/router/example/', $func);


$router->any("{$link}сlass_1/",                 'test\classes\test_class_1@method_0');
$router->any("{$link}сlass_1/{}/",              'test\classes\test_class_2@method_1');
$router->any("{$link}сlass_1/{val_2}/{val}/",   'test\classes\test_class_2@method_2');
$router->any("{$link}сlass_2/",                 'test\classes\test_class_2@method_3');
$router->any("{$link}сlass_2/{}/",              'test\classes\test_class_2@method_3');
$router->any("{$link}сlass_3/",                 null);

//print_r($_SERVER);

$func = function ($val) use ($link) {
	echo "val - {$val}<br>";
};
$router->any("{$link}{}/", $func);



$func = function ($val, $val_2) use ($link) {
	echo "val - {$val}<br>";
	echo "val_2 - {$val_2}<br>";
};
$router->any("{$link}{val_2}/{val}/", $func);

//var_dump($router);

$func = function () {
	echo "Вывод страницы по умолчанию";
};
# Заглавная страница
$router->add_default($func);

# Вызов обработчика маршрутизатора (перед этим указываем страницу по умолчанию)
try {
	echo $content = $router->router();
} catch (\Exception $e) {
	echo "<b>ОШИБКА:</b> {$e->getMessage()}";
	echo '<hr>';
//	$router->router_or_default();
}
