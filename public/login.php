<?php
session_start();
if (isset($_SESSION['owner'])) { header('Location: admin.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($login === 'ms' && $pass === '123') {
        $_SESSION['owner'] = true;
        header('Location: admin.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — АТМОСФЕРА</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    .login-wrap{min-height:100vh;display:grid;place-items:center;background:var(--cream)}
    .login-card{width:100%;max-width:400px;padding:50px 40px;background:var(--paper);box-shadow:0 8px 40px rgba(38,51,45,.1)}
    .login-card h1{font:28px Prata,serif;margin:0 0 8px}
    .login-card p{font-size:13px;color:#68736d;margin:0 0 30px}
    .login-card label{display:block;font-size:12px;font-weight:700;margin-bottom:14px}
    .login-card input[type=text],.login-card input[type=password]{display:block;width:100%;margin-top:8px;padding:14px;border:1px solid var(--line);background:transparent;outline-color:var(--green);font:inherit}
    .login-card .button{width:100%;margin-top:24px}
    .login-error{color:#bd4040;font-size:13px;margin-top:14px}
    .login-back{display:block;text-align:center;margin-top:20px;font-size:12px;color:#68736d}
    </style>
</head>
<body>
<div class="login-wrap">
    <form class="login-card" method="post" action="login.php">
        <h1>Вход для владельца</h1>
        <p>Введите данные для доступа к панели управления</p>
        <label>Логин<input type="text" name="login" required autocomplete="username"></label>
        <label>Пароль<input type="password" name="password" required autocomplete="current-password"></label>
        <button class="button" type="submit">Войти</button>
        <?php if (!empty($error)): ?><p class="login-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <a class="login-back" href="index.php">← Вернуться в магазин</a>
    </form>
</div>
</body>
</html>