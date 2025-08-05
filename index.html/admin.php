<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['approve_user_id'])) {
    $approve_id = intval($_POST['approve_user_id']);
    $mysqli->query("UPDATE users SET is_approved = 1, reject_reason = NULL WHERE id = $approve_id");
    header("Location: admin.php");
    exit();
}

if (isset($_POST['reject_user_id']) && isset($_POST['reject_reason'])) {
    $reject_id = intval($_POST['reject_user_id']);
    $reason = $mysqli->real_escape_string($_POST['reject_reason']);
    $mysqli->query("UPDATE users SET is_approved = 0, reject_reason = '$reason' WHERE id = $reject_id");
    header("Location: admin.php");
    exit();
}

$result = $mysqli->query("SELECT id, email, is_approved, is_banned, reject_reason FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Админ-панель</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
    form { margin: 0; }
    textarea { width: 100%; height: 50px; }
    button { padding: 5px 10px; margin-top: 5px; }
  </style>
</head>
<body>
  <h2>Админ-панель — Пользователи</h2>
  <table>
    <tr><th>ID</th><th>Email</th><th>Статус</th><th>Бан</th><th>Причина отклонения</th><th>Действия</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= $row['is_approved'] ? 'Подтверждён' : 'Ожидает подтверждения' ?></td>
        <td><?= $row['is_banned'] ? 'Да' : 'Нет' ?></td>
        <td><?= nl2br(htmlspecialchars($row['reject_reason'])) ?: '—' ?></td>
        <td>
          <?php if (!$row['is_approved']): ?>
            <form method="post" style="margin-bottom: 10px;">
              <input type="hidden" name="approve_user_id" value="<?= $row['id'] ?>" />
              <button type="submit">Подтвердить</button>
            </form>

            <form method="post">
              <input type="hidden" name="reject_user_id" value="<?= $row['id'] ?>" />
              <textarea name="reject_reason" placeholder="Причина отклонения..." required></textarea>
              <button type="submit">Отклонить</button>
            </form>
          <?php else: ?>
            —
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>