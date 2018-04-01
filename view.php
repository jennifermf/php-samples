<?php
require_once "pdo.php";
require_once "util.php";
session_start();

$row = loadProfile($pdo, $_GET['gig_id']);
$title = htmlentities($row['title']);
$comp = htmlentities($row['company']);
$sub = htmlentities($row['submitted']);
$resp = htmlentities($row['response']);
$info = htmlentities($row['info']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("head.html"); ?>
<meta charset = "UTF-8">
</head>
<body>
<h1>Job Application</h1>
<table class="view">
<tr><td>Title:</td><td><?= $title ?></td></tr>
<tr><td>Company:</td><td><?= $comp ?></td></tr>
<tr><td>Submitted:</td><td><?= $sub ?></td></tr>
<tr><td>Response Date:</td><td><?= $resp ?></td></tr>
<tr><td>Info:</td><td><?= $info ?></td></tr>
</table>
    <h3><a href="edit.php?gig_id=<?= $row['gig_id'] ?>">edit</a> | <a href="delete.php?gig_id=<?= $row['gig_id'] ?>">delete</a> | <a href="index.php">home</a></h3>

</body>
