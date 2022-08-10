<?php

declare(strict_types=1);
require 'databaseConnection.php ';
require 'functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    include 'pageNotFound.php';
}

$memberSqlQuery = "SELECT forename, surname, joined, picture FROM member WHERE id = :id;";
$member = pdo($pdo, $memberSqlQuery, [$id])->fetch();

if (!$member) {
    include 'pageNotFound.php';
}

$articleSqlQuery = "SELECT a.id, a.title, a.summary, a.category_id, a.member_id,
               c.name AS category,
               CONCAT(m.forename, ' ', m.surname) AS author,
               i.file AS image_file,
               i.alt AS image_alt
        FROM article AS a
        JOIN category AS c ON a.category_id = c.id
        JOIN member AS m ON a.memmber_id = m.id   
        LEFT JOIN image AS i ON a.image_id = i.id
        WHERE a.member_id = :id AND a.published = 1
        ORDER BY a.id DESC;";

$articles = pdo($pdo, $articleSqlQuery, [$id])->fetchAll();
$navigationSqlQuery = "SELECT id, name FROM category WHERE navigation = 1;";
$navigation = pdo($pdo, $navigationSqlQuery)->fetchAll();
$section = '';
$title = $member['forename'] . ' ' . $member['surname'];
$description = $title . ' on Creative Folk';
?>

<?php include 'header.php'; ?>
<main class="container" id="content">
    <section class="header">
        <h1><?= html_escape($member['forename'] . ' ' . $member['surname']) ?></h1>
        <p class="member"><b>Member since:</b><?= format_date($member['joined']) ?></p>
        <img src="uploads/<?= html_escape($member['picture'] ?? 'blank.png') ?>" alt="<?= html_escape($member['forename']) ?>" class="profile"><br>
    </section>
    <section class="grid">
        <?php foreach ($articles as $article) : ?>

        <?php endforeach ?>
    </section>
</main>
<?php include 'footer.php'; ?>