<?php
session_start();
include('db.php');

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $mysqli->prepare("SELECT id, password, is_admin, is_approved, is_banned, reject_reason FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "Неверный логин или пароль.";
    exit();
}

$stmt->bind_result($user_id, $hash, $is_admin, $is_approved, $is_banned, $reject_reason);
$stmt->fetch();

if ($is_banned) {
    echo "Ваш аккаунт заблокирован.";
    exit();
}

if (!$is_approved) {
    $reasonMsg = $reject_reason ? "<br><b>Причина отказа:</b> " . htmlspecialchars($reject_reason) : "";
    echo "Ваш аккаунт еще не подтверждён администратором." . $reasonMsg;
    exit();
}

if (password_verify($password, $hash)) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['is_admin'] = $is_admin;
    header("Location: index.html");
    exit();
} else {
    echo "Неверный логин или пароль.";
}
?>