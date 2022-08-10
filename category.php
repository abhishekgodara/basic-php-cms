<?php

declare(strict_types=1);
require 'databaseConnection.php';
require 'functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "page not found";
    exit;
}

$sql = "SELECT id, name, description FROM category WHERE id=:id;";
$category = pdo($pdo, $sql, [$id])->fetch();
if (!$category) {
    echo "page not found";
    exit;
}

$sql = "SELECT a.id, a.title, a.summary, a.category_id, a.member_id,
c.name AS category,
CONCAT(m.forename,' ', m.surname) AS author,
i.file AS image_file,
i.alt AS image_alt
FROM article AS a
JOIN category AS c ON a.category_id = c.id
JOIN member AS m ON a.member_id = m.id
LEFT JOIN image AS i ON a.image_id = i.id
WHERE a.category_id = :id AND a.published = 1
ORDER BY a.id DESC;";

$articles = pdo($pdo, $sql, [$id])->fetchAll();

$sql = "SELECT id, name FROM category WHERE navigation=1";
$navigation = pdo($pdo, $sql)->fetchAll();
$section = $category['id'];
$title = $category['name'];
$description = $category['description'];
?>

<?php include 'header.php'; ?>
<main class="container" id="content">
    <section class="header">
        <h1><?= html_escape($category['name']) ?></h1>
        <p><?= html_escape($category['description']) ?></p>
    </section>
    <section class="grid">
        <?php foreach ($articles as $article) { ?>
            <article class="summary">
                <a href="article.php" ?id=<?= $article['id'] ?>">
                    <img src="uploads/<?= html_escape($article['img_file'] ?? 'blank.png') ?>" alt="<?= html_escape($article['image_alt']) ?>">
                    <h2><?= html_escape($article['title']) ?></h2>
                    <p><?= html_escape($article['summary']) ?></p>
                </a>
            <?php } ?>