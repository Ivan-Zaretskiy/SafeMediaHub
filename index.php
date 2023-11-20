<?php
//$asd = 'asd';
//$memcached = new Memcached();
//var_dump($memcached);
phpinfo();
die();

// Создаем новый объект Memcached
//$memcached = new Memcached();
//
//// Добавляем сервер Memcached (замените localhost и 11211, если ваш сервер Memcached расположен по другому адресу)
//$memcached->addServer('memcached', 11211);
//var_dump($memcached);
//// Пример использования Memcached для сохранения и получения данных
//$key = 'example_key';
//$data = 'Hello, Memcached!';
//
//// Сохраняем данные в кэше
//$memcached->set($key, $data, 3600); // Время жизни: 3600 секунд (1 час)
//
//// Получаем данные из кэша
//$result = $memcached->get($key);
//
//// Выводим результат
//var_dump($result);
//die();
include_once ('core/config.php');
ApplicationHelper::init();
ApplicationHelper::handle();
ApplicationHelper::exit();