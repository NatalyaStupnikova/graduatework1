<?php

require_once('login.php');

$name = htmlspecialchars($_POST['name']);
$phone = htmlspecialchars($_POST['phone']);
$email = htmlspecialchars($_POST['email']);
$street = htmlspecialchars($_POST['street']);
$home = (int)($_POST['home']);
$part = htmlspecialchars($_POST['part']);
$appt = (int)($_POST['appt']);
$floor = (int)($_POST['floor']);
$comment = htmlspecialchars($_POST['comment']);

if ($_POST['payment'] == 'oddmoney') {
    $payment = 'Нужна сдача';
} elseif ($_POST['payment'] == 'card') {
    $payment = 'Оплата картой';
} else {
    $payment = 'Не указано';
}

if ($_POST['callback'] == 'notcall') {
    $callback = 'Не перезванивать';
} else {
    $callback = 'Не указано';
}

//проверка на зарегестрированность

$sql = 'SELECT * FROM users WHERE email = ?';
$query = $pdo->prepare($sql);
$query->execute((array)$email);
$answer = $query->fetch(PDO::FETCH_ASSOC);

if ($answer['id'] === null) {

    // регистрация пользователя
    $userInfo = [
        'email' => $email,
        'name' => $name,
        'tel' => $phone
    ];
    $sql = 'INSERT INTO users (email, name, tel) VALUES (:email, :name, :tel)';
    $query = $pdo->prepare($sql);
    $query->execute($userInfo);

    //узнаем присвоенный id
    $sql = 'SELECT * FROM users WHERE email = ?';
    $query = $pdo->prepare($sql);
    $query->execute((array)$email);
    $answer = $query->fetch(PDO::FETCH_ASSOC);

}

//оформляем заказ
$orderInfo = [
    'id' => $answer['id'],
    'street' => $street,
    'home' => $home,
    'part' => $part,
    'appt' => $appt,
    'floor' => $floor,
    'comment' => $comment,
    'payment' => $payment,
    'callback' => $callback
];

$sql = 'INSERT INTO orders (id, street, home, part, appt, floor, comment, payment, callback) VALUES (:id, :street, :home, :part, :appt, :floor, :comment, :payment, :callback)';
$query = $pdo->prepare($sql);
$query->execute($orderInfo);


//узнаем количество заказов пользователя и id текущего заказа
$sql = 'SELECT idorders FROM orders WHERE id = ?';
$query = $pdo->prepare($sql);
$query->execute((array)$answer['id']);
$count = $query->rowCount();
for ($i = 0; $i < $count; $i++) {
    $arrayId = $query->fetch(PDO::FETCH_ASSOC);
}

//формируем письмо
$subject = 'Заказ №' . $arrayId['idorders'];
$message = "Заказ: DarkBeefBurger за 500 рублей, 1 шт. Это ваш $count заказ";
mail($email, $subject, $message);
