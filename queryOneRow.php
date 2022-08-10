<?php
require 'databaseConnection.php';
require 'functions.php';

$sql = "SELECT forename, surname
        FROM member
        WHERE id = 1;";

$statement = $pdo->query($sql);
$member = $statement->fetch();
?>

<!DOCTYPE html>
<html>

<body>
    <p>
        <? html_escape($member['forename']) ?>
        <? html_escape($member['surname'])  ?>
    </p>
</body>

</html>