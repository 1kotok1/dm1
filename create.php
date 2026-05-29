<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transport = $_POST['transport'];
    $date = $_POST['date'];
    $payment = $_POST['payment'];

    $stmt = $pdo->prepare("INSERT INTO applications (user_id, transport_name, start_date, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $transport, $date, $payment]);
    $success = 'Заявка создана! <a href="dashboard.php">Мои заявки</a>';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая заявка - Водить.РФ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </header>

    <main>
        <div class="form">
            <?php if ($success): ?>
                <div style="color:green"><?= $success ?></div>
            <?php endif; ?>
            <form method="post">
                <select name="transport" required>
                    <option value="">Выберите транспорт</option>
                    <option>Вождение катеров</option>
                    <option>Вождение круизных лайнеров</option>
                    <option>Вождение яхт</option>
                </select>
                <input type="date" name="date" required>
                <label><input type="radio" name="payment" value="cash" required> Наличными</label>
                <label><input type="radio" name="payment" value="transfer"> Перевод по номеру телефона</label>
                <button type="submit">Отправить заявку</button>
            </form>
            <a href="dashboard.php">Назад</a>
        </div>
    </main>

    <footer>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </footer>
</body>
</html>