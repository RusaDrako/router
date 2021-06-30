# RusaDrako\\router
Маршрутизатор

## Подключение

Для подключения библиотеки к проекту подключите файл `src/autoload.php`

## Доступные классы

| Псевдоним | Полное имя класса |
| :---: | :--- |
| RD_Router_Core | RusaDrako\\router\\router_core |
| RD_Router | RusaDrako\\router\\router_add |

`RusaDrako\\router\\router_add` наследует от `RusaDrako\\router\\router_core`


## Начало работы

```php
	$router = new RD_Router();
```


### Методы объекта RD_Router_Core


#### set_root_folder

Задаёт корневую папку для поиска классов

```php
	$router->set_root_folder($value);
```

**$value** - Корневая папка для поиска классов


#### set_route

Задаёт текущий маршрут

```php
	$router->set_route($value);
```

**$value** - Текущий маршрут для обработки


#### set_type_rest

Задаёт текущий тип REST

```php
	$router->set_type_rest($value);
```

**$value** - Текущий тип REST


#### add_router

Добавляет маршрут

```php
	$router->add_router($type, $route_mask, $action);
```

**$type** - Тип REST
**$route_mask** - Маршрут (маска)
**$action** - Связанное действие


#### add_default

Задаёт страницу по умолчанию

```php

	$router->add_default($action, $type);
```

**$action** - Связанное действие
**$type** - Тип REST (необязательный)


#### get_group

Выводит наименование уровня маршрута

```php

	$router->get_group($num);
```

**$num** - Номер уровня (необязательный)


#### router

Обрабатывает текущий маршрута

```php

	$router->router();
```


#### Доступные типы REST

`GET`, `HEAD`, `POST`, `PUT`, `PATCH`, `DELETE`, `OPTIONS`.





### Методы объекта RD_Router


#### set_server_setting

Задаёт настройки по настройкам сервера

```php
	$router->set_server_setting();
```

Выполняет настройки объекта путём вызова методов с настройками на основе настроек сервера:

```php
	$this->set_route(...);
	$this->set_root_folder(...);
	$this->set_type_rest(...);
	$this->set_route(...);
```


#### any

Задаёт маршруты для всех типов REST

```php
	$router->any($route_mask, $action);
```

**$route_mask** - Маршрут (маска)
**$action** - Связанное действие

#### get

Задаёт маршруты для GET
```php
	$router->get($route_mask, $action);
```

**$route_mask** - Маршрут (маска)
**$action** - Связанное действие

#### post

Задаёт маршруты для POST

```php
	$router->post($route_mask, $action);
```

**$route_mask** - Маршрут (маска)
**$action** - Связанное действие
