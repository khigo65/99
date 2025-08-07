<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Создание таблиц
$queries = [
    // Таблица категорий
    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL UNIQUE,
        icon VARCHAR(50),
        description TEXT,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    // Таблица контента (статусы, SMS, факты)
    "CREATE TABLE IF NOT EXISTS content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT,
        title VARCHAR(255),
        text TEXT NOT NULL,
        type ENUM('status', 'sms', 'fact', 'sound') NOT NULL,
        views INT DEFAULT 0,
        likes INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
    )",
    
    // Таблица администраторов
    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        last_login TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    // Таблица настроек сайта
    "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT,
        description VARCHAR(255)
    )",
    
    // Таблица статистики
    "CREATE TABLE IF NOT EXISTS statistics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page VARCHAR(100),
        visits INT DEFAULT 0,
        date DATE,
        UNIQUE KEY unique_page_date (page, date)
    )"
];

try {
    foreach ($queries as $query) {
        $db->exec($query);
    }
    
    // Вставка начальных данных
    $categories_data = [
        ['Классные статусы', 'statuses', 'kl.png', 'Классные статусы - отборные, отсортированные по категориям статусы со всего интернета.', 1],
        ['Прикольные смс', 'funny-sms', 'pr.png', 'Прикольные смс - коллекция смешных, ярких и красочных смс!', 2],
        ['Смс любимым', 'love-sms', 'lu.png', 'Смс любимым - это красивые оригинальные смски для общения с дорогими вам людьми.', 3],
        ['Смс поздравления', 'congratulations', 'pz.png', 'Смс поздравления - куча поздравительных смс на любые торжества.', 4],
        ['Интересные факты', 'facts', 'fc.png', 'Интересные факты - подборка самых интересных и невероятных фактов о людях.', 5],
        ['Звуки на смс', 'sounds', 'zv.png', 'Звуки на смс - отличная сборка коротких звуков для смс оповещений.', 6]
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO categories (name, slug, icon, description, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($categories_data as $cat) {
        $stmt->execute($cat);
    }
    
    // Создание админа по умолчанию
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT IGNORE INTO admins (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $admin_password, 'admin@statusms.ru']);
    
    // Настройки сайта
    $settings_data = [
        ['site_title', 'Классные статусы и СМС', 'Название сайта'],
        ['online_users', '347', 'Количество онлайн пользователей'],
        ['meta_description', 'Классные статусы про любовь и жизнь, прикольные SMS', 'Описание сайта'],
        ['admin_email', 'admin@statusms.ru', 'Email администратора']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
    foreach ($settings_data as $setting) {
        $stmt->execute($setting);
    }
    
    echo "База данных успешно создана!<br>";
    echo "Логин админа: admin<br>";
    echo "Пароль админа: admin123<br>";
    echo "<a href='index.php'>Перейти на сайт</a> | <a href='admin/'>Админ-панель</a>";
    
} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>