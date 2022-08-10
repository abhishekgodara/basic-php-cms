<?php
require 'databaseConnection.php';
require 'functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "page not found";
    exit;
}

$sql = "SELECT forename, surname FROM member WHERE id = :id;";

$member = pdo($pdo, $sql, ['id' => $id])->fetch();
if (!$member) {
    echo "Page not found";
    exit;
}
?>
<p>
    <?= $member['forename']; ?>
</p>