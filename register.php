<?php
session_start();
require 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $data = trim($_POST['data']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $login)) {
        $error = 'Логин должен содержать латиницу и цифры, не менее 6 символов';
    } elseif (strlen($password) < 8) {
        $error = 'Пароль должен быть не менее 8 символов';
    } elseif (!preg_match('/^[а-яА-ЯёЁ\s]+$/u', $fullname)) {
        $error = 'ФИО должно содержать только кириллицу и пробелы';
    } elseif (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
        $error = 'Телефон должен быть в формате 8(XXX)XXX-XX-XX';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Неверный формат email';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $error = 'Логин уже занят';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (login, password, fullname, data_r, phone, email, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");
            $stmt->execute([$login, $hash, $fullname, $data, $phone, $email]);
            $success = 'Регистрация успешна! <a href="login.php">Войти</a>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Водить.РФ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </header>

    <main>
        <div class="form">
            <?php if ($error): ?>
                <div style="color:red"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div style="color:green"><?= $success ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="text" name="login" placeholder="Логин (латиница, ≥6)" required>
                <input type="password" name="password" placeholder="Пароль (≥8 символов)" required>
                <input type="text" name="fullname" placeholder="ФИО" required>
                <input type="date" name="data" required>
                <input type="tel" name="phone" placeholder="Телефон 8(XXX)XXX-XX-XX" required>
                <input type="email" name="email" placeholder="Почта" required>
                <button type="submit">Зарегистрироваться</button>
            </form>
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </div>
    </main>

    <footer>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </footer>
</body>
</html>