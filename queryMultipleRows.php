<?php

require 'databaseConnection.php';
require 'functions.php';

$sql = "SELECT forename, surname
        FROM member;";

$statement = $pdo->query($sql);
$members = $statement->fetchAll();

?>
<!DOCTYPE html>

<html>

<body>
    <?php foreach ($members as $member) { ?>
        <p>
            <? html_escape($member['forename']) ?>
            <? html_escape($member['surname']) ?>
        </p>
    <?php } ?>
</body>

</html>