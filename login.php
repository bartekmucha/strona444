<?php
require_once 'config.php';
require_once 'session_handler.php';

$handler = new MySQLSessionHandler($pdo);
session_set_save_handler($handler, true);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = ?");
    $stmt->execute([$user]);
    $acc = $stmt->fetch();

    if ($acc && hash('sha256', $pass) === $acc['password']) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user;
        header("Location: index.php");
        exit;
    } else {
        $error = "Nieprawidłowy login lub hasło!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h2>Logowanie</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <label>Login:</label>
        <input type="text" name="username" required>
        <label>Hasło:</label>
        <input type="password" name="password" required>
        <button type="submit">Zaloguj</button>
    </form>
</div>
</body>
</html>
