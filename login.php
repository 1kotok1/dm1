<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($login === 'Admin26' && $password === 'Demo20') {
        $_SESSION['user_id'] = 1;
        $_SESSION['login'] = 'Admin26';
        $_SESSION['role'] = 'admin';
        header('Location: admin.php');
        exit;
    }
    elseif ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Водить.РФ</title>
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
            <form method="post">
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit">Войти</button>
            </form>
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </div>
    </main>

    <footer>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </footer>
</body>
</html>