<?php
require 'databaseConnection.php';
require 'functions.php';

$term = filter_input(INPUT_GET, 'term') ?? '';
$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ?? 3;
$from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT) ?? 0;
$count = 0;
$articles = [];

if ($term) {
    $arguments['term1'] = "%{$term}%";
    $arguments['term2'] = "%{$term}%";
    $arguments['term3'] = "%{$term}%";

    $sql = "SELECT COUNT(title) FROM article
            WHERE title LIKE :term1
            OR summary LIKE :term2
            OR content LIKE :term3
            AND published = 1;";

    $count = pdo($pdo, $sql, $arguments)->fetchColumn();
    if ($count > 0) {
        $arguments['show'] = $show;
        $arguments['from'] = $from;

        $sql = "SELECT a.id, a.title, a.summary, a.category_id, a.member_id,
            c.name AS category,
            CONCAT(m.forename, ' ', m.surname) AS author,
            i.file AS image_file,
            i.alt AS image_alt
            FROM article AS a
            JOIN category AS c ON a.category_id = c.id
            JOIN member AS m ON a.member_id = m.id   
            LEFT JOIN image AS i ON a.image_id = i.id
            WHERE a.title LIKE :term1
            OR a.summary LIKE :term2
            OR a.content LIKE :term3
            AND a.published = 1
            ORDER BY a.id DESC
            LIMIT :show
            OFFSET :from;";

        $articles = pdo($pdo, $sql, $arguments)->fetchAll();
    }
}
if ($count > $show) {
    $total_pages = ceil($count / $show);
    $current_page = ceil($from / $show) + 1;
}
$sql = "SELECT id, name FROM category WHERE navigation = 1;";
$navigation = pdo($pdo, $sql)->fetchAll();

$section = '';
$title = 'Search results for' . $term;
$description = $title . ' on Creative Folk';
?>

<?php include 'header.php'; ?>
<main class="container" id="content">
    <section class="header">
        <form action="search.php" method="get" class="form-search">
            <label for="search"><span>Search for:</span></label>
            <input type="text" name="term" value="<?= html_escape($term) ?>" id="search" placeholder="Enter Search Term" />
            <input type="submit" value="search" class="btn" />
        </form>
        <?php if ($term) : ?><p><b>Matches found:</b><?= $count ?></p><?php endif; ?>
    </section>

    <section class="grid">
        <?php foreach ($articles as $article) : ?>
            <p>
                <a href="article.php?id=<?= $article['id'] ?>">
                    <img src="uploads/<?= html_escape($article['image_file'] ?? 'blank.png') ?>" alt=" <?= html_escape($article['image_alt']) ?>">
                    <h2> <?= html_escape($article['title']) ?></h2>
                    <p><?= html_escape($article['summary']) ?></p>
                </a>
            </p>
        <?php endforeach ?>
    </section>

    <?php if ($count > $show) : ?>
        <nav class="pagination" role="navigation" aria-label="pagination navigation">
            <ul>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <?php $offset = ($i - 1) * $show ?>
                    <li>
                        <a href="?term=<?= $term ?>&show=<?= $show ?>&from=<?= $offset ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>

        </nav>
    <?php endif; ?>
</main>