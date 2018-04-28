<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['gig_id'])) {

    // Data validation
    if ( strlen($_POST['title']) < 1 || strlen($_POST['company']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: index.php");
        return;
    }

    $sql = "UPDATE Gigs SET title = :title, company = :comp, submitted = :sub, response = :resp, info = :info WHERE gig_id = :gid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':title' => $_POST['title'],
        ':comp' => $_POST['company'],
        ':sub' => $_POST['submitted'],
        ':resp' => $_POST['response'],
        ':info' => $_POST['info'],
        ':gid' => $_GET['gig_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Gigs where gig_id = :gig_id");
$stmt->execute(array(":gig_id" => $_GET['gig_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for gig_id';
    header( 'Location: index.php' ) ;
    return;
}

$title = htmlentities($row['title']);
$comp = htmlentities($row['company']);
$sub = htmlentities($row['submitted']);
$resp = htmlentities($row['response']);
$info = htmlentities($row['info']);
$gig_id = $_POST['gig_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset = "UTF-8">
<script src = "jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="calendar.css">
<script src="scripts.js"></script>
<meta charset = "UTF-8">
</head>
<body>
<p>Edit Entry</p>
<form method="post" value="editrecord">
<p>Title:
<input type="text" name="title" value="<?= $title ?>"></p>
<p>Company:
<input type="text" name="company" value="<?= $comp ?>"></p>
<p>Submitted (YYYY-MM-DD):
<input type="text" name="submitted" class="datepicker" value="<?= $sub ?>"></p>
<p>Response (YYYY-MM-DD):
<input type="text" name="response" class="datepicker" value="<?= $resp ?>"></p>
<p>Info:</p>
<textarea rows="10" cols="50" name="info"><?= $info ?></textarea>
<input type="hidden" name="gig_id" value="<?= $gig_id ?>">
<p><input type="submit" value="Update"/>
<a href="index.php">Cancel</a></p>
</form>
</body>
