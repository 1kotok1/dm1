<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['login'] !== 'Admin26' && $_SESSION['role'] !== 'admin')) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['app_id']]);
}

$stmt = $pdo->query("SELECT a.*, u.login, u.fullname FROM applications a JOIN users u ON a.user_id = u.id");
$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель - Водить.РФ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ - Админ панель</h1>
        <p>Добро пожаловать, Admin!</p>
        <a href="logout.php">Выйти</a>
    </header>

    <main>
        <div class="cards">
            <?php foreach ($applications as $app): ?>
                <div class="card">
                    <p><strong>Заявка №<?= $app['id'] ?></strong></p>
                    <p><strong>Пользователь:</strong> <?= $app['login'] ?> (<?= $app['fullname'] ?>)</p>
                    <p><strong>Транспорт:</strong> <?= $app['transport_name'] ?></p>
                    <p><strong>Дата:</strong> <?= $app['start_date'] ?></p>
                    <p><strong>Статус:</strong> <?= $app['status'] ?></p>
                    <form method="post">
                        <input type="hidden" name="app_id" value="<?= $app['id'] ?>">
                        <select name="status">
                            <option value="Новая" <?= $app['status'] == 'Новая' ? 'selected' : '' ?>>Новая</option>
                            <option value="Идет обучение" <?= $app['status'] == 'Идет обучение' ? 'selected' : '' ?>>Идет обучение</option>
                            <option value="Обучение завершено" <?= $app['status'] == 'Обучение завершено' ? 'selected' : '' ?>>Обучение завершено</option>
                        </select>
                        <button type="submit">Изменить статус</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </footer>
</body>
</html>