<?php
require 'databaseConnection.php';
require 'functions.php';

$sql = "SELECT id, forename, surname, joined, picture FROM memeber;";
$statement = $pdo->query($sql);
$members = $statement->fetchAll();
?>
<!DOCTYPE html>

<body>
    <?php foreach ($members as $member) { ?>
        <div class="member-summary">
            <h2><?= $member['forename'] ?></h2>
            <p>Member since:<br><?= format_date($member['joined']) ?><< /p>
        </div>
    <?php } ?>
</body>