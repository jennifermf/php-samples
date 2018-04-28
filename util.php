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

function autocomplete($searchTerm) {
    // this needs work
    $query = $db->query("SELECT * FROM Gigs WHERE name LIKE '%".$searchTerm."%' ORDER BY name ASC");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['name'];
    }
    //return json data
    echo json_encode($data)
}
