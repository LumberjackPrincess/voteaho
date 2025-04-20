<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission and insert data into the database
    if (isset($_POST['input1'])) {
        $input1 = pg_escape_string($_POST['input1']);

// Query to get elecid from position
        $query2 = "Select castvote.elecid FROM tv.castvote INNER JOIN tv.election ON castvote.elecid = election.elecid WHERE election.position = $1";
        $prep2 = pg_prepare($conn, "remid", $query2);
        if (!$prep2) {
    	echo "Error prep query: " . pg_last_error($conn);
    	exit;}
        $result2 = pg_execute($conn, "remid", array($input1));
        if (!$result2) {
    	echo "Error prep query: " . pg_last_error($conn);
    	exit;}
        $row = pg_fetch_assoc($result2);
        $elecid = $row['elecid'];
        
// Query to delete from cast vote
	$query1 = "DELETE FROM tv.castvote WHERE castvote.elecid = $1";
	$prepare1 = pg_prepare($conn, "remelc1", $query1);
	if (!$prepare1) {
    	echo "Error prep query: " . pg_last_error($conn);
    	exit;}
	$result1 = pg_execute($conn, "remelc1", array($elecid));
	if (!$result1) {
    	echo "Error prep query: " . pg_last_error($conn);
    	exit;}

// Query to delete election data from the database
        $query = "DELETE FROM tv.election WHERE election.position = $1";
        $prepare = pg_prepare($conn, "remelec", $query);
        if (!$prepare) {
    	echo "Error prep query: " . pg_last_error($conn);
    	exit;
}
        $result = pg_execute($conn, "remelec", array($input1));

        if ($result) {
            // Redirect back to the main page with a success alert
            echo "<script>alert('Election Removed!'); window.location.href = 'admhome.php';</script>";
            exit;
        } else {
        echo "An error occurred: " . pg_last_error($conn);
            //echo "<script>alert('No Election Data Found!'); window.location.href = 'admhome.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove an Election</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<h1>Remove a Election</h1>
<form method="POST">
    <label for="input1">Position:</label>
    <input type="text" id="input1" name="input1" required>
    
    <button type="submit">Skoden</button>
</form>

</body>
<div>
<a class="btn admsin" href="admhome.php">Cancel</a>
</div>
<div class="footer">
    <p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
    </div>
</html>
