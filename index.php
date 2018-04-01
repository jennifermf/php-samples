<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("head.html"); ?>
<meta charset = "UTF-8">
<title>Jen's Job Search</title>
</head>
<body>
    <div class="top">
    <h1>Jen's Job Search Database</h1>
    <h2>starring PHP, SQL, JavaScript, jQuery, HTML, CSS</h2>
    <div class="count"></div>
    <h3><a href="add.php">Add a new job application.</a></h3>
    </div>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
?>
<table>
    <tr>
        <th>Title</th>
        <th>Company</th>
        <th>Submitted</th>
        <th>Response</th>
    </tr>
    <tbody id="mytable">
    </tbody>
</table>
</body>
<script type="text/javascript">
// Simple htmlentities leveraging JQuery
function htmlentities(str) {
   return $('<div>').text(str).html();
}
// Do this *after* the table tag is rendered
$.getJSON('getjson.php', function(rows) {
    $("#mytable").empty();
    console.log(rows);
    found = false;
    var waiting = 0;
    for (var i = 0; i < rows.length; i++) {
        row = rows[i];
        found = true;
        window.console && console.log('Row: '+i+' '+row.title);
        $("#mytable").append('<tr><td><a href="view.php?gig_id='+row.gig_id+'">'
            + htmlentities(row.title)+'<br/><a href="edit.php?gig_id='+htmlentities(row.gig_id)+'">Edit</a> | <a href="delete.php?gig_id='+htmlentities(row.gig_id)+'">Delete</a></td><td>'
            + htmlentities(row.company)+'</td><td>'
            + htmlentities(row.submitted)+'</td><td class="' + row.gig_id + '">'
            + htmlentities(row.response)+'</td></tr>');
        if (row.response == '0000-00-00') {
            ++waiting;
        }
    }
    /* TODO: filter based on response date */
    if ( ! found ) {
        $("#mytable").append("<tr><td>No entries found</td></tr>\n");
    }
    $(".count").append('<h3>' + (rows.length) + ' submissions to date. Waiting for ' + waiting + ' responses.</h3>');
});
</script>
