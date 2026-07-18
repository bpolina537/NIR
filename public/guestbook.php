<?php

declare(strict_types=1);

require 'lib/database.php';

$connection = database();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim((string) ($_POST['author'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($message === '') {
        $error = 'Сообщение не может быть пустым';
    } elseif (strlen($message) > 4000) {
        $error = 'Сообщение не должно превышать 2000 символов';
    } else {
        $author = $author === '' ? 'Анонимно' : substr($author, 0, 120);
        $statement = $connection->prepare('INSERT INTO guestbook_messages (author, message) VALUES (?, ?)');
        $statement->execute([$author, $message]);
        header('Location: guestbook.php?added=1');
        exit;
    }
}

$messages = $connection->query(
    'SELECT id, author, message, created_at FROM guestbook_messages ORDER BY id DESC LIMIT 50'
)->fetchAll();

$title = 'Отзывы — Атмосфера';
$page = 'reviews';
require 'partials/header.php';
?>
<section class="page-hero compact">
    <span class="eyebrow">Мнения покупателей</span>
    <h1>Отзывы</h1>
    <p>Расскажите о покупке или поделитесь впечатлением о магазине</p>
</section>
<section class="guestbook-layout client-guestbook">
    <form class="php-form" method="post" action="guestbook.php">
        <h2>Новое сообщение</h2>
        <label>Имя
            <input type="text" name="author" maxlength="120" placeholder="Необязательно">
        </label>
        <label>Сообщение
            <textarea name="message" maxlength="2000" required placeholder="Введите сообщение"><?= htmlspecialchars((string) ($_POST['message'] ?? '')) ?></textarea>
        </label>
        <button class="button" type="submit">Добавить сообщение</button>
        <?php if ($error !== ''): ?><div class="form-result error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if (isset($_GET['added'])): ?><div class="form-result">Сообщение добавлено</div><?php endif; ?>
    </form>
    <div class="guestbook-list">
        <h2>Сообщения</h2>
        <?php if ($messages === []): ?>
            <div class="empty-state">В гостевой книге пока нет сообщений</div>
        <?php endif; ?>
        <?php foreach ($messages as $item): ?>
            <article class="guestbook-message">
                <header>
                    <strong><?= htmlspecialchars($item['author']) ?></strong>
                    <time datetime="<?= htmlspecialchars($item['created_at']) ?>"><?= date('d.m.Y H:i', strtotime($item['created_at'])) ?></time>
                </header>
                <p><?= htmlspecialchars($item['message']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require 'partials/footer.php'; ?>
