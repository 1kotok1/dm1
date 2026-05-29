<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
    $review = trim($_POST['review']);
    $app_id = $_POST['app_id'];
    if (!empty($review)) {
        $stmt = $pdo->prepare("UPDATE applications SET review = ? WHERE id = ? AND user_id = ? AND status = 'Обучение завершено'");
        $stmt->execute([$review, $app_id, $user_id]);
    }
}

$stmt = $pdo->prepare("SELECT * FROM applications WHERE user_id = ?");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки - Водить.РФ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
        <p>Добро пожаловать, <?= $_SESSION['login'] ?>!</p>
    </header>

    <main>
        <div class="slider-container">
            <img id="slide" src="images/1.jpg" alt="Слайд">
            <button class="slider-btn prev" id="prev">❮</button>
            <button class="slider-btn next" id="next">❯</button>
        </div>

        <div class="buttons">
            <a href="create.php">+ Новая заявка</a>
            <a href="logout.php">Выйти</a>
        </div>

        <?php if (empty($applications)): ?>
            <p>У вас пока нет заявок. <a href="create.php">Создать заявку</a></p>
        <?php else: ?>
            <div class="cards">
                <?php foreach ($applications as $app): ?>
                    <div class="card">
                        <p><strong>Статус:</strong> <?= $app['status'] ?></p>
                        <p><strong>Транспорт:</strong> <?= $app['transport_name'] ?></p>
                        <p><strong>Дата начала:</strong> <?= $app['start_date'] ?></p>
                        <p><strong>Оплата:</strong> <?= $app['payment_method'] === 'cash' ? 'Наличными' : 'Переводом' ?></p>
                        <?php if ($app['review']): ?>
                            <p><strong>Отзыв:</strong> <?= $app['review'] ?></p>
                        <?php elseif ($app['status'] === 'Обучение завершено'): ?>
                            <form method="post">
                                <input type="hidden" name="app_id" value="<?= $app['id'] ?>">
                                <textarea name="review" placeholder="Оставьте отзыв"></textarea>
                                <button type="submit">Отправить отзыв</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <img src="logo.png" class="logo" alt="Логотип">
        <h1>Водить.РФ</h1>
    </footer>

    <script src="script.js"></script>
</body>
</html>