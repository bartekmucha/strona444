<?php
require_once 'config.php';
require_once 'session_handler.php';

$handler = new MySQLSessionHandler($pdo);
session_set_save_handler($handler, true);
session_start();

session_destroy();
header("Location: login.php");
exit;
?>
