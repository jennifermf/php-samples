<?php

function loadProfile($pdo, $gig_id) {
    $stmt = $pdo->prepare("SELECT * FROM Gigs where gig_id = :gig_id");
    $stmt->execute(array(":gig_id" => $_GET['gig_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for gig_id';
        header( 'Location: index.php' ) ;
        return;
    }
    return $row;
}
