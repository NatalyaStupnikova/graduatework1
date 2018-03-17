<?php

require_once('login.php');
header('Content-Type: text/html; charset=utf-8');

$sql = 'SELECT * FROM users';
$query = $pdo->prepare($sql);
$query->execute();
$answer = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<h1>Выгрузка информации из БД</h1>';
echo '<h2>Пользователи</h2>';

echo '<table border="1">';
echo '<tr><td>Id пользователя</td><td>Имя</td><td>Телефон</td><td>E-mail</td></tr>';
foreach ($answer as $value) {
    echo '<tr>';
    foreach ($value as $value2) {
        echo '<td>' .$value2.'</td>';
    }
    echo '</tr>';
}
echo '</table>';

$sql = 'SELECT * FROM orders';
$query = $pdo->prepare($sql);
$query->execute();
$answer = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<h2>Заказы</h2>';

echo '<table border="1">';
echo '<tr><td>Id заказа</td><td>Id пользователя</td><td>Улица</td><td>Дом</td><td>Корпус</td><td>Квартира</td>';
echo '<td>Этаж</td><td>Комментарий</td><td>Оплата</td><td>Звонок</td></tr>';
foreach ($answer as $value) {
    echo '<tr>';
    foreach ($value as $value2) {
        echo '<td>' .$value2.'</td>';
    }
    echo '</tr>';
}
echo '</table>';
