<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function formatDate($date) {
    return date('d.m.Y H:i', strtotime($date));
}

function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function updateStatistics($page) {
    global $db;
    $today = date('Y-m-d');
    
    $query = "INSERT INTO statistics (page, visits, date) VALUES (?, 1, ?) 
              ON DUPLICATE KEY UPDATE visits = visits + 1";
    $stmt = $db->prepare($query);
    $stmt->execute([$page, $today]);
}

function getOnlineUsers() {
    global $settings;
    return $settings->get('online_users') ?: '347';
}
?>