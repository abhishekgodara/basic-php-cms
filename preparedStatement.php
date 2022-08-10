<?php
require 'databaseConnection.php';

$id = 1;
$sql = "SELECT forename, surname
         FROM member
         WHERE id=:id;";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$member = $statement->fetch();

if (!$member) {
    echo "Page not found";
}
?>
<!DOCTYPE html>
<html>

<body>
    <p>
        <?= $member['forename'] ?>
        <?= $member['surname'] ?>
    </p>
</body>

</html>