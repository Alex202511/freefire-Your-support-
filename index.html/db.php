<?php
$mysqli = new mysqli("localhost", "root", "", "support_db");
if ($mysqli->connect_error) {
    die("Ошибка подключения к БД: " . $mysqli->connect_error);
}
?>