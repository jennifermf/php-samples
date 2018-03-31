<?php
/* util.php is a mixed collection of functions used with SQL CRUD applications. */

function validateProfile() {
    // confirm not blank:
    if ( (strlen($_POST['first_name']) < 1 ) || (strlen($_POST['last_name']) < 1 ) || (strlen($_POST['email']) < 1) || (strlen($_POST['headline']) < 1) || (strlen($_POST['summary']) < 1)) {
        return "All fields are required";
    }
    // check for properly formatted email:
    if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) ) {
        return "Email address must contain @";
    }
    else {
        return true;
    }
}

function validateEdu() {
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['edyear'.$i]) ) continue;
        if ( ! isset($_POST['edu_school'.$i]) ) continue;
        $edyear = $_POST['edyear'.$i];
        $institution = $_POST['edu_school'.$i];
        if ( (strlen($edyear) < 1 ) || (strlen($institution) < 1 ) ) {
            return "All fields are required";
        }
        if ( ! is_numeric($edyear) ) {
            return "Year must be numeric";
        }
    }
    return true;
}

function validatePos() {
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if ( (strlen($year) == 0) || (strlen($desc) == 0) ) return "All fields are required";
        if ( ! is_numeric($year) ) return "Year must be numeric";
    }
    return true;
}

function loadEdu($pdo, $profile_id) {
    /* replacing:
    $institutions = array();
    // make an array of institutions associated with :pid
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $institutions[] = $row;
    ...with fetchAll() */
    $stmt = $pdo->prepare('SELECT year, name FROM Education JOIN Institution
        ON Education.institution_id = Institution.institution_id
        WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute(array(':pid'=>$profile_id));
    $institutions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $institutions;
}

function loadPos($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute(array(':pid'=>$profile_id));
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $positions;
}

function addProfile($pdo) {
    $sql = "INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary)
            VALUES ( :uid, :fn, :ln, :em, :he, :su)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']));
}

function addEdu($pdo, $profile_id) {
    $edrank = 1;
    for ($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['edyear'.$i]) ) continue;
        if ( ! isset($_POST['edu_school'.$i]) ) continue;
        $edyear = $_POST['edyear'.$i];
        $institution = $_POST['edu_school'.$i];

        // try to look up school
        $institution_id = false;
        $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name = :name');
        $stmt->execute(array(':name'=>$institution));
        $row = $stmt->FETCH(PDO::FETCH_ASSOC);
        if ($row !== false) $institution_id = $row['institution_id'];

        // insert if school does not exist
        else {
            $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
            $stmt->execute(array(':name'=>$institution));
            $institution_id = $pdo->lastInsertId();
        }

        // now add everything to education table
        $stmt = $pdo->prepare('INSERT INTO Education (profile_id, rank, year, institution_id) VALUES (:pid, :rank, :year, :iid)');
        $stmt->execute(array(
            ':pid'=>$profile_id,
            ':rank'=>$edrank,
            ':year'=>$edyear,
            ':iid'=>$institution_id));
        $edrank++;
    }
    return;
}

function addPos($pdo, $profile_id) {
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :descr)');
        $stmt->execute(array(
            ':pid'=>$profile_id,
            ':rank'=>$rank,
            ':year'=>$year,
            ':descr'=>$desc));
        $rank++;
    }
    $_SESSION["success"] = "Record added";
    header("Location: index.php");
    return;
}

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
