<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['user_id']) && isset($_POST['ban'])) {
    $user_id = intval($_POST['user_id']);
    $ban = intval($_POST['ban']); // 0 или 1

    $mysqli->query("UPDATE users SET is_banned = $ban WHERE id = $user_id");
}

header("Location: admin.php");
exit();
?>