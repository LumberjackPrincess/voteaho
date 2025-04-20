<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';
//$position = '';

// Initialize variables
$position = '';
$candidates = [];

// Ensure you have received the election name (position)
if (isset($_POST['election_name'])) {
    $position = urldecode($_POST['election_name']);

    // Query to get candidates for the selected election position
    $QCandidates = "SELECT candname FROM tv.candidate INNER JOIN tv.election ON candidate.candid = election.candid WHERE election.position = $1";
    $QResult = pg_prepare($conn, "get_candidates", $QCandidates);
    $QExecute = pg_execute($conn, "get_candidates", array($position));

    if (!$QExecute) {
        echo "An error occurred.\n";
        echo "Error: " . pg_last_error($conn);
        exit;
    }
    //$row = pg_fetch_all($QExecute);
    //$candidates = $row['candname'];
    $candidates = pg_fetch_all($QExecute);
    if ($candidates === false || count($candidates) === 0) {
        echo "No candidates found for this election.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote for <?php echo $position; ?></title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<div class="container">
    <h1>Vote for <?php echo $position; ?></h1>
    <form action="submitvote.php" method="POST">
        <input type="hidden" name="election_name" value="<?php echo $position; ?>">

        <label for="voter_choice">Gwaanden, pick one:</label>
        <select name="voter_choice" required>
            <option value="">-- Select a Candidate --</option>
            <?php foreach ($candidates as $candidate): ?>
                <option value="<?php echo htmlspecialchars($candidate['candname']); ?>">
                    <?php echo htmlspecialchars($candidate['candname']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn signup" type="submit">Submit Vote</button>
    </form>
</div>

</body>
<a class="btn admsin" href="welcome.php">Cancel</a>
<div class="footer">
    <p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
    </div>
</html>


