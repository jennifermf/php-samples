<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['gig_id']) ) {
    $sql = "DELETE FROM Gigs where gig_id = :gig_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':gig_id' => $_POST['gig_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['gig_id']) ) {
  $_SESSION['error'] = "Missing gig_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT title, company, gig_id FROM Gigs where gig_id = :gig_id");
$stmt->execute(array(":gig_id" => $_GET['gig_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for gig_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("head.html"); ?>
<meta charset = "UTF-8">
</head>
<body class="delete">
<p>Confirm delete:</p>
<p class="fancytxt"><?= htmlentities($row['title']) ?> @ <?= htmlentities($row['company']) ?></p>

<form method="post">
<input type="hidden" name="gig_id" value="<?= $row['gig_id'] ?>">
<input class = "delete" type="submit" value="Delete" name="delete">
or
<a href="index.php">Cancel</a>
</form>
</body>
