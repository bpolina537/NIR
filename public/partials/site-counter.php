<?php

require_once dirname(__DIR__) . '/lib/database.php';
date_default_timezone_set('Europe/Moscow');

$siteHits = null;
try {
    $counterDatabase = database();
    $counterDatabase->exec("INSERT INTO page_hits (page_key, hits) VALUES ('client_site', 1)
        ON DUPLICATE KEY UPDATE hits = hits + 1");
    $counterStatement = $counterDatabase->prepare('SELECT hits FROM page_hits WHERE page_key = ?');
    $counterStatement->execute(['client_site']);
    $siteHits = (int) $counterStatement->fetchColumn();
} catch (Throwable $error) {
    $siteHits = null;
}
?>
<?php if ($siteHits !== null): ?>
    <div class="site-counter" id="site-counter">
        Страница была загружена <?= $siteHits ?> раз. Текущее время <?= date('H:i') ?>.
    </div>
<?php endif; ?>
