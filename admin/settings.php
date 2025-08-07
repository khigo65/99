<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Settings.php';
require_once '../includes/functions.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$settings = new Settings($db);

$message = '';

if ($_POST) {
    foreach ($_POST as $key => $value) {
        if ($key !== 'submit') {
            $settings->set($key, sanitize_input($value));
        }
    }
    $message = 'Настройки сохранены';
}

$all_settings = $settings->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Настройки - Админ-панель</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>Настройки сайта</h1>
        <div class="admin-nav">
            <a href="index.php">Главная</a>
            <a href="content.php">Контент</a>
            <a href="categories.php">Категории</a>
            <a href="settings.php" class="active">Настройки</a>
            <a href="logout.php">Выход</a>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="settings-form">
            <form method="post">
                <?php foreach ($all_settings as $setting): ?>
                    <div class="form-group">
                        <label><?php echo htmlspecialchars($setting['description'] ?: $setting['setting_key']); ?>:</label>
                        <?php if (strlen($setting['setting_value']) > 100): ?>
                            <textarea name="<?php echo $setting['setting_key']; ?>" rows="3"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                        <?php else: ?>
                            <input type="text" name="<?php echo $setting['setting_key']; ?>" value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                        <?php endif; ?>
                        <small>Ключ: <?php echo $setting['setting_key']; ?></small>
                    </div>
                <?php endforeach; ?>
                
                <button type="submit" name="submit">Сохранить настройки</button>
            </form>
        </div>

        <div class="admin-info">
            <h3>Информация о системе</h3>
            <p><strong>PHP версия:</strong> <?php echo phpversion(); ?></p>
            <p><strong>Время сервера:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>Администратор:</strong> <?php echo $_SESSION['admin_username']; ?></p>
        </div>
    </div>
</body>
</html>