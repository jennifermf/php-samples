<?php
require_once "pdo.php";
session_start();

if (isset($_POST['title']) && isset($_POST['company']) ) {
    // Data validation
    if ( strlen($_POST['title']) < 1 || strlen($_POST['company']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO Gigs (title, company, submitted, response, info)
              VALUES (:title, :comp, :sub, :resp, :info)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':title' => $_POST['title'],
        ':comp' => $_POST['company'],
        ':sub' => $_POST['submitted'],
        ':resp' => $_POST['response'],
        ':info' => $_POST['info']));
    $_SESSION['success'] = 'Record Added';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("head.html"); ?>
<meta charset = "UTF-8">
</head>
<body>
<p>Add A New Job Application</p>
<form method="post" value="addrecord">
<p>Title:
<input type="text" name="title"></p>
<p>Company:
<input type="text" name="company"></p>
<p>Submitted (YYYY-MM-DD):
<input type="text" name="submitted"></p>
<p>Response (YYYY-MM-DD):
<input type="text" name="response"></p>
<p>Info:</p>
<textarea rows="10" cols="50" name="info"></textarea>
<p><input type="submit" value="Add New"/>
<a href="index.php">Cancel</a></p>
</form>
</body>
